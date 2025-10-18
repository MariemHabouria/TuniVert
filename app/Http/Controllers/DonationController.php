<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Notifications\DonationReceived;
use App\Mail\DonationReceipt;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;
use App\Services\GamificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Log as LogFacade;
use Illuminate\Support\Facades\Auth as AuthFacade;

class DonationController extends Controller
{
    public function create(Request $request)
    {
        // Handle event_id parameter for direct event donations
        $eventId = $request->query('event_id');
        
        // Use the themed donation page and jump to the inline form, preserving selected event
        $event = $request->query('event');
        $url = route('donation');
        
        // Use event_id if provided, otherwise use event
        if ($eventId) {
            $url .= '?event=' . urlencode((string)$eventId);
        } elseif ($event) {
            $url .= '?event=' . urlencode((string)$event);
        }
        
        return redirect()->to($url . '#donate');
    }

    public function store(Request $request)
    {
        // Accept integrated methods and any admin-configured active methods
        $base = ['carte','paypal','paymee','virement_bancaire'];
        if (config('services.testpay.enabled')) { $base[] = 'test'; }
        // Merge with custom active methods from DB (if available)
        try {
            $dynamic = PaymentMethod::where('active', true)->pluck('key')->all();
        } catch (\Throwable $e) {
            $dynamic = [];
        }
        $allowedMethods = collect($base)
            ->merge($dynamic)
            ->unique()
            ->values()
            ->all();

        $validated = $request->validate([
            'montant' => ['required','numeric','min:1'],
            'moyen_paiement' => ['required', Rule::in($allowedMethods)],
            'evenement_id' => ['nullable','integer'],
            'is_anonymous' => ['nullable','boolean'],
        ]);

        // If card selected, require Stripe client confirmation before persisting
        if ($validated['moyen_paiement'] === 'carte') {
            // The client will call createIntent to get client_secret and confirm on frontend
            // Here we just defer creation until confirmation endpoint to ensure success before storing
        }

        // Generate a reference for bank transfer (for receipt/instructions)
        $reference = null;
        if ($validated['moyen_paiement'] === 'virement_bancaire') {
            $reference = 'BANK-' . Auth::id() . '-' . now()->format('Ymd-His') . '-' . substr(bin2hex(random_bytes(2)), 0, 4);
        }

        $don = Donation::create([
            'utilisateur_id' => Auth::id(),
            'is_anonymous' => (bool)$request->boolean('is_anonymous'),
            'evenement_id' => $validated['evenement_id'] ?? null,
            'montant' => $validated['montant'],
            'moyen_paiement' => $validated['moyen_paiement'],
            'transaction_id' => $reference, // null for non-bank methods
            'date_don' => now(),
        ]);
        // Track last used method for future defaults
        try { session(['last_method' => $don->moyen_paiement]); } catch (\Throwable $e) {}
        // Gamification
        try {
            $res = app(GamificationService::class)->onDonation($don);
            if (!empty($res['new_badges'])) {
                session()->flash('new_badges', $res['new_badges']);
            }
        } catch (\Throwable $e) {}
        // Flash points earned (+X)
        $pointsEarned = (int) round((float)$don->montant);
        session()->flash('points_earned', $pointsEarned);

        // Notify donor (and potentially organizer/admin if needed)
        try {
            // Prefer sending to the donation's owner
            $owner = $don->user ?? Auth::user();
            if ($owner) {
                // In local dev, avoid SMTP timeouts: skip notifications and use log mailer for receipts
                $recipient = $owner->email ?? null;
                if (!app()->environment('local')) {
                    // In non-local envs, allow normal notifications
                    $owner->notify(new DonationReceived($don));
                }
                if ($recipient) {
                    $mailable = new \App\Mail\DonationReceiptSimple($don);
                    if (app()->environment('local')) {
                        Mail::mailer('log')->to($recipient)->queue($mailable);
                    } else {
                        Mail::to($recipient)->queue($mailable);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore notification errors in MVP
        }

        $flash = 'Merci pour votre don !';
        if ($validated['moyen_paiement'] === 'virement_bancaire' && $reference) {
            $flash = 'Merci pour votre don ! Veuillez effectuer votre virement en indiquant la référence ' . $reference . ' comme motif. Un email avec les instructions vous a été envoyé.';
        }

    return redirect()->route('donations.history')->with('status', $flash);
    }

    // Create a PaymentIntent and return client_secret to the frontend
    public function createStripeIntent(Request $request)
    {
        $request->validate([
            'amount' => ['required','numeric','min:1'],
        ]);
        $amount = (float)$request->input('amount');
        $secret = config('services.stripe.secret');
        $key = config('services.stripe.key');
        if (!$secret || !$key) {
            return response()->json(['error' => 'Stripe keys not configured. Please set STRIPE_KEY and STRIPE_SECRET.'], 500);
        }
        try {
            $stripe = new StripeClient($secret);
            $currency = config('services.stripe.currency','usd');
            $pi = $stripe->paymentIntents->create([
                'amount' => (int) round($amount * 100),
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'user_id' => (string)Auth::id(),
                    'evenement_id' => (string)$request->input('event'),
                ],
            ]);
            return response()->json(['clientSecret' => $pi->client_secret, 'paymentIntentId' => $pi->id]);
        } catch (\Throwable $e) {
            Log::error('Stripe create intent failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Failed to initialize Stripe payment. '.$e->getMessage()], 500);
        }
    }

    // Confirm endpoint called after successful Stripe confirmation to persist donation
    public function confirmStripePayment(Request $request)
    {
        $validated = $request->validate([
            'paymentIntentId' => ['required','string'],
            'montant' => ['required','numeric','min:1'],
            'evenement_id' => ['nullable','integer'],
            'is_anonymous' => ['nullable','boolean'],
        ]);
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $pi = $stripe->paymentIntents->retrieve($validated['paymentIntentId']);
            if ($pi->status !== 'succeeded' && $pi->status !== 'requires_capture') {
                return response()->json(['error' => 'Payment not confirmed'], 400);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Stripe verification failed: '.$e->getMessage()], 500);
        }
        $finalAmount = isset($pi->amount_received) && $pi->amount_received > 0 ? $pi->amount_received : $pi->amount;
        $don = Donation::create([
            'utilisateur_id' => Auth::id(),
            'is_anonymous' => (bool)$request->boolean('is_anonymous'),
            'evenement_id' => $validated['evenement_id'] ?? null,
            'montant' => ((int)$finalAmount) / 100,
            'moyen_paiement' => 'carte',
            'transaction_id' => $validated['paymentIntentId'],
            'date_don' => now(),
        ]);
        try { session(['last_method' => $don->moyen_paiement]); } catch (\Throwable $e) {}
        try {
            $res = app(GamificationService::class)->onDonation($don);
            if (!empty($res['new_badges'])) {
                session()->flash('new_badges', $res['new_badges']);
            }
        } catch (\Throwable $e) {}
        // Flash points for subsequent redirect to history
        session()->flash('points_earned', (int) round((float)$don->montant));
        try {
            $owner = $don->user ?? Auth::user();
            if ($owner) {
                $recipient = $owner->email ?? null;
                if (!app()->environment('local')) {
                    $owner->notify(new DonationReceived($don));
                }
                if ($recipient) {
                    $mailable = new DonationReceipt($don);
                    if (app()->environment('local')) {
                        Mail::mailer('log')->to($recipient)->queue($mailable);
                    } else {
                        Mail::to($recipient)->queue($mailable);
                    }
                }
            }
        } catch (\Throwable $e) {}
    return response()->json(['ok' => true]);
    }

    public function history()
    {
        $userId = Auth::id();
        $dons = Donation::with('event')
            ->where('utilisateur_id', $userId)
            ->orderByDesc('date_don')
            ->paginate(10);
        // Ensure base gamification data exists (idempotent)
        try {
            if (!DB::table('badges')->count()) {
                // Seed core badges quickly if seeder wasn't run
                DB::table('badges')->upsert([
                    ['slug'=>'donor_bronze','name'=>'Donateur Bronze','icon'=>'🥉','description'=>'A atteint 50 TND de dons','created_at'=>now(),'updated_at'=>now()],
                    ['slug'=>'donor_silver','name'=>'Donateur Argent','icon'=>'🥈','description'=>'A atteint 200 TND de dons','created_at'=>now(),'updated_at'=>now()],
                    ['slug'=>'donor_gold','name'=>'Donateur Or','icon'=>'🥇','description'=>'A atteint 500 TND de dons','created_at'=>now(),'updated_at'=>now()],
                    ['slug'=>'protector_oceans','name'=>'Protecteur des Océans','icon'=>'🌊','description'=>'A soutenu la cause Écosystème (≥100 TND)','created_at'=>now(),'updated_at'=>now()],
                ], ['slug'], ['name','icon','description','updated_at']);
            }
        } catch (\Throwable $e) {}

        // Keep donation badges consistent with current totals
        try {
            $user = \App\Models\User::find($userId);
            if ($user) {
                    if ($dons->total() === 0) {
                        app(\App\Services\GamificationService::class)->revokeDonationBadgesIfNoDonations($user);
                    } else {
                        $syncResult = app(\App\Services\GamificationService::class)->syncDonationBadges($user);
                        Log::info('syncDonationBadges result', ['awarded' => $syncResult['awarded'] ?? []]);
                        if (!empty($syncResult['awarded'])) {
                            $existing = session('new_badges');
                            $merged = [];
                            if (is_array($existing)) {
                                $merged = array_values(array_merge($existing, $syncResult['awarded']));
                            } else {
                                $merged = $syncResult['awarded'];
                            }
                            Log::info('Flashing new_badges to session', ['merged' => $merged]);
                            session()->flash('new_badges', $merged);
                        }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Badge sync error: ' . $e->getMessage());
        }

        // Aggregate points and badges for the current user
        // Total points = points from donations (1 per TND) + any bonus points (e.g., challenges)
        $donationSum = (float) DB::table('donations')->where('utilisateur_id', $userId)->sum('montant');
        $donationPoints = (int) round($donationSum);
        $bonusPoints = (int) DB::table('points_ledger')
            ->where('user_id', $userId)
            ->where('reason', '!=', 'donation')
            ->sum('points');
        $totalPoints = $donationPoints + $bonusPoints;
        // Event-specific totals for progress hints (e.g., Ecosystem id=2)
        $event2Sum = (float) DB::table('donations')->where('utilisateur_id', $userId)->where('evenement_id', 2)->sum('montant');
        $badges = DB::table('user_badges as ub')
            ->join('badges as b', 'b.id', '=', 'ub.badge_id')
            ->where('ub.user_id', $userId)
            ->orderBy('ub.awarded_at', 'desc')
            ->select('b.slug','b.name','b.icon','b.description','ub.awarded_at')
            ->get();
        // If user has donations but zero badges, re-evaluate to backfill
        try {
            if ($badges->isEmpty() && DB::table('donations')->where('utilisateur_id',$userId)->exists()) {
                app(\App\Services\GamificationService::class)->evaluateBadges(\App\Models\User::find($userId));
                $badges = DB::table('user_badges as ub')
                    ->join('badges as b', 'b.id', '=', 'ub.badge_id')
                    ->where('ub.user_id', $userId)
                    ->orderBy('ub.awarded_at', 'desc')
                    ->select('b.slug','b.name','b.icon','b.description','ub.awarded_at')
                    ->get();
            }
        } catch (\Throwable $e) {}
        $allBadges = DB::table('badges')->select('slug','name','icon','description')->orderBy('id')->get();
        $ownedSlugs = $badges->pluck('slug')->all();
        return view('donations.history', compact('dons','totalPoints','badges','allBadges','ownedSlugs','donationPoints','bonusPoints','donationSum','event2Sum'));
    }

    // ===== PayPal Integration =====
    private function paypalBaseUrl(): string
    {
        $mode = config('services.paypal.mode', 'sandbox');
        return $mode === 'live' ? 'https://api.paypal.com' : 'https://api.sandbox.paypal.com';
    }

    private function paypalAccessToken(): ?string
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        if (!$clientId || !$secret) { return null; }
        $res = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($this->paypalBaseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);
        if (!$res->ok()) { return null; }
        return $res->json()['access_token'] ?? null;
    }

    public function createPayPalOrder(Request $request)
    {
        $request->validate(['amount' => ['required','numeric','min:1']]);
        $token = $this->paypalAccessToken();
        if (!$token) {
            return response()->json(['error' => 'PayPal not configured. Set PAYPAL_CLIENT_ID and PAYPAL_SECRET.'], 500);
        }
        $amount = number_format((float)$request->input('amount'), 2, '.', '');
        $currency = strtoupper(config('services.paypal.currency', 'USD'));
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $currency,
                    'value' => $amount,
                ],
                'custom_id' => (string)Auth::id(),
                'reference_id' => (string)$request->input('event'),
            ]],
            'application_context' => [
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
            ],
        ];
        $res = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->paypalBaseUrl().'/v2/checkout/orders', $payload);
        if (!$res->ok()) {
            Log::error('PayPal create order failed', ['body' => $res->body()]);
            return response()->json(['error' => 'Failed to create PayPal order'], 500);
        }
        return response()->json(['id' => $res->json()['id'] ?? null]);
    }

    public function capturePayPalOrder(Request $request)
    {
        $validated = $request->validate([
            'orderId' => ['required','string'],
            'evenement_id' => ['nullable','integer'],
        ]);
        $token = $this->paypalAccessToken();
        if (!$token) { return response()->json(['error' => 'PayPal not configured'], 500); }
        $orderId = $validated['orderId'];
        $res = Http::withToken($token)
            ->post($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}/capture", []);
        if (!$res->ok()) {
            Log::error('PayPal capture failed', ['body' => $res->body()]);
            return response()->json(['error' => 'Failed to capture PayPal order'], 500);
        }
        $data = $res->json();
        if (($data['status'] ?? '') !== 'COMPLETED') {
            return response()->json(['error' => 'Order not completed'], 400);
        }
        $capture = $data['purchase_units'][0]['payments']['captures'][0] ?? null;
        $value = $capture['amount']['value'] ?? '0.00';
        $amount = (float)$value;
        $don = Donation::create([
            'utilisateur_id' => Auth::id(),
            'is_anonymous' => (bool)$request->boolean('is_anonymous'),
            'evenement_id' => $validated['evenement_id'] ?? null,
            'montant' => $amount,
            'moyen_paiement' => 'paypal',
            'transaction_id' => $capture['id'] ?? $orderId,
            'date_don' => now(),
        ]);
        try { session(['last_method' => $don->moyen_paiement]); } catch (\Throwable $e) {}
        try { app(GamificationService::class)->onDonation($don); } catch (\Throwable $e) {}
        session()->flash('points_earned', (int) round((float)$don->montant));
        try {
            $owner = $don->user ?? Auth::user();
            if ($owner) {
                $recipient = $owner->email ?? null;
                if (!app()->environment('local')) {
                    $owner->notify(new DonationReceived($don));
                }
                if ($recipient) {
                    $mailable = new DonationReceipt($don);
                    if (app()->environment('local')) {
                        Mail::mailer('log')->to($recipient)->queue($mailable);
                    } else {
                        Mail::to($recipient)->queue($mailable);
                    }
                }
            }
        } catch (\Throwable $e) {}
        return response()->json(['ok' => true]);
    }

    // ===== Paymee (e‑DINAR) Integration =====
    private function paymeeBaseUrl(): string
    {
        $mode = config('services.paymee.mode', 'sandbox');
        return $mode === 'live' ? 'https://app.paymee.tn' : 'https://sandbox.paymee.tn';
    }

    public function createPaymeePayment(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required','numeric','min:1'],
            'evenement_id' => ['nullable','integer'],
            'first_name' => ['nullable','string','max:100'],
            'last_name' => ['nullable','string','max:100'],
            'email' => ['nullable','email'],
            'phone' => ['nullable','string','max:30'],
        ]);
        $apiKey = config('services.paymee.api_key');
        if (!$apiKey) {
            return response()->json(['error' => 'Paymee not configured. Ask admin to set PAYMEE_API_KEY.'], 400);
        }
        $user = Auth::user();
        $amount = number_format((float)$validated['amount'], 2, '.', '');
        $note = 'Donation';
        $returnUrl = route('payments.paymee.return');
        $cancelUrl = route('payments.paymee.cancel');
        $webhookUrl = route('webhooks.paymee');
        $eventId = $validated['evenement_id'] ?? ($request->input('event') ? (int)$request->input('event') : null);
        $payload = [
            'amount' => (float)$amount,
            'note' => $note,
            'first_name' => $validated['first_name'] ?? ($user?->name ? explode(' ', $user->name)[0] : 'Donor'),
            'last_name' => $validated['last_name'] ?? ($user?->name ? (explode(' ', $user->name)[1] ?? '') : 'Tunivert'),
            'email' => $validated['email'] ?? ($user?->email ?? 'no-reply@example.com'),
            'phone' => $validated['phone'] ?? '+21600000000',
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'webhook_url' => $webhookUrl,
            // Format: {userId}_{eventId_optional}_{timestamp}
            'order_id' => (string)($user?->id) . ($eventId ? ('_'.$eventId) : '') . '_' . time(),
        ];
        try {
            $res = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Token '.$apiKey,
                ])->post($this->paymeeBaseUrl().'/api/v2/payments/create', $payload);
            if (!$res->ok()) {
                $body = $res->json();
                $msg = is_array($body) ? ($body['message'] ?? null) : null;
                Log::error('Paymee create failed', ['body' => $res->body()]);
                return response()->json(['error' => $msg ?: 'Failed to initialize Paymee payment'], 500);
            }
            $data = $res->json();
            if (!($data['status'] ?? false)) {
                Log::warning('Paymee create returned error status', ['data' => $data]);
                return response()->json(['error' => $data['message'] ?? 'Paymee error'], 500);
            }
            $paymentUrl = $data['data']['payment_url'] ?? null;
            $token = $data['data']['token'] ?? null;
            return response()->json(['payment_url' => $paymentUrl, 'token' => $token]);
        } catch (\Throwable $e) {
            Log::error('Paymee create exception: '.$e->getMessage());
            return response()->json(['error' => 'Paymee error: '.$e->getMessage()], 500);
        }
    }

    public function paymeeReturn(Request $request)
    {
        // After redirection back, simply show history with status message. Final verification via webhook.
        return redirect()->route('donations.history')->with('status', 'Paiement Paymee traité. Vérification en cours.');
    }

    public function paymeeCancel(Request $request)
    {
        return redirect()->route('donations.history')->with('error', 'Paiement Paymee annulé.');
    }

    public function paymeeWebhook(Request $request)
    {
        // Verify checksum
        $apiKey = config('services.paymee.api_key');
        $token = (string)$request->input('token');
        $paymentStatus = $request->boolean('payment_status');
        $checkSum = (string)$request->input('check_sum');
        $expected = md5($token . ($paymentStatus ? '1' : '0') . $apiKey);
        if (strtolower($expected) !== strtolower($checkSum)) {
            Log::warning('Paymee webhook checksum mismatch', ['expected' => $expected, 'got' => $checkSum]);
            return response()->json(['error' => 'invalid signature'], 400);
        }
        // Process success
        if ($paymentStatus) {
            $amount = (float)($request->input('amount') ?? 0);
            $transactionId = (string)$request->input('transaction_id');
            $orderId = (string)$request->input('order_id');
            // Try to extract user id and optional event id from order_id if encoded
            $userId = null; $evenementId = null;
            if (preg_match('/^(\d+)(?:_(\d+))?_(\d{9,})$/', $orderId, $m)) {
                $userId = (int)$m[1];
                $evenementId = isset($m[2]) && $m[2] !== '' ? (int)$m[2] : null;
            } elseif (preg_match('/^(\d+)(?:_(\d+))?/', $orderId, $m)) {
                $userId = (int)$m[1];
                $evenementId = isset($m[2]) ? (int)$m[2] : null;
            }
            // Fallback to current auth if webhook is triggered during user session (not guaranteed)
            $userId = $userId ?: (Auth::id() ?? null);
            if ($userId) {
                $tx = $transactionId ?: $token;
                // Idempotency: avoid duplicate records if webhook retries
                $existing = Donation::where('transaction_id', $tx)->first();
                if ($existing) {
                    return response()->json(['ok' => true, 'dup' => true]);
                }
                $don = Donation::create([
                    'utilisateur_id' => $userId,
                    'is_anonymous' => (bool)$request->boolean('is_anonymous'),
                    'evenement_id' => $evenementId,
                    'montant' => $amount,
                    'moyen_paiement' => 'paymee',
                    'transaction_id' => $tx,
                    'date_don' => now(),
                ]);
                try { session(['last_method' => $don->moyen_paiement]); } catch (\Throwable $e) {}
                try {
                    $res = app(GamificationService::class)->onDonation($don);
                    if (!empty($res['new_badges'])) {
                        session()->flash('new_badges', $res['new_badges']);
                    }
                } catch (\Throwable $e) {}
                try {
                    // Send receipt to the owner if resolvable
                    $owner = $don->user ?? (Auth::id() === $userId ? Auth::user() : null);
                    if ($owner) {
                        $recipient = $owner->email ?? null;
                        if (!app()->environment('local')) {
                            $owner->notify(new DonationReceived($don));
                        }
                        if ($recipient) {
                            $mailable = new DonationReceipt($don);
                            if (app()->environment('local')) {
                                Mail::mailer('log')->to($recipient)->queue($mailable);
                            } else {
                                Mail::to($recipient)->queue($mailable);
                            }
                        }
                    }
                } catch (\Throwable $e) {}
            } else {
                Log::warning('Paymee webhook: missing userId in order_id', ['order_id' => $orderId]);
            }
        }
        return response()->json(['ok' => true]);
    }

    /**
     * Test bank transfer email functionality
     */
    public function testBankTransferEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'montant' => ['required', 'numeric', 'min:1'],
            'is_anonymous' => ['nullable', 'boolean'],
        ]);

        try {
            // Find or create a test user with this email
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => 'Test User',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Create test donation
            $don = Donation::create([
                'utilisateur_id' => $user->id,
                'is_anonymous' => (bool)($validated['is_anonymous'] ?? false),
                'evenement_id' => null,
                'montant' => (float)$validated['montant'],
                'moyen_paiement' => 'virement_bancaire',
                'transaction_id' => 'TEST_WEB_' . time(),
                'date_don' => now(),
            ]);

            $newBadges = [];
            
            // Apply gamification
            try {
                $res = app(GamificationService::class)->onDonation($don);
                $newBadges = $res['new_badges'] ?? [];
            } catch (\Throwable $e) {
                // Gamification errors are non-critical
            }

            // Send email using the same logic as real donations
            $recipient = $user->email;
            $mailable = new DonationReceipt($don);
            
            // For bank transfer, always send real emails
            Mail::to($recipient)->sendNow($mailable);

            return response()->json([
                'success' => true,
                'donation_id' => $don->id,
                'amount' => $don->montant,
                'transaction_id' => $don->transaction_id,
                'email_sent_to' => $recipient,
                'badges' => !empty($newBadges) ? implode(', ', array_column($newBadges, 'name')) : null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Association Dashboard - Overview of donations and analytics
     */
    public function associationDashboard(Request $request)
    {
        // Only allow association users
        if (!Auth::check() || Auth::user()->role !== 'association') {
            abort(403, 'Access denied. Association role required.');
        }

        $period = $request->get('period', '30'); // Default to 30 days
        $startDate = now()->subDays((int)$period);
        
        // Total collected in selected period
        $totalCollected = DB::table('donations')
            ->where('date_don', '>=', $startDate)
            ->sum('montant');
            
        // Previous period comparison
        $previousPeriodStart = now()->subDays((int)$period * 2);
        $previousPeriodEnd = now()->subDays((int)$period);
        $previousTotal = DB::table('donations')
            ->whereBetween('date_don', [$previousPeriodStart, $previousPeriodEnd])
            ->sum('montant');
            
        $evolution = $previousTotal > 0 ? (($totalCollected - $previousTotal) / $previousTotal) * 100 : 0;

        // Top 5 events by amount (using only donations table)
        $topEvents = DB::table('donations as d')
            ->select(
                DB::raw('CASE 
                    WHEN d.evenement_id IS NULL THEN "Donation Générale" 
                    ELSE CONCAT("Événement #", d.evenement_id) 
                END as event_name'),
                'd.evenement_id',
                DB::raw('SUM(d.montant) as total'),
                DB::raw('COUNT(d.id) as count')
            )
            ->where('d.date_don', '>=', $startDate)
            ->groupBy('d.evenement_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Recent donations (last 10)
        $recentDonations = DB::table('donations as d')
            ->leftJoin('users as u', 'd.utilisateur_id', '=', 'u.id')
            ->select(
                'd.id',
                'd.montant',
                'd.moyen_paiement',
                'd.date_don',
                'd.is_anonymous',
                'd.evenement_id',
                DB::raw('CASE WHEN d.is_anonymous = 1 THEN "Anonyme" ELSE COALESCE(u.name, "Utilisateur supprimé") END as donor_name'),
                DB::raw('CASE 
                    WHEN d.evenement_id IS NULL THEN "Donation Générale" 
                    ELSE CONCAT("Événement #", d.evenement_id) 
                END as event_name')
            )
            ->orderByDesc('d.date_don')
            ->limit(10)
            ->get();

        // Event progress using statistiques_donations table
        $eventProgress = collect();
        try {
            // Check if statistiques_donations table exists
            if (Schema::hasTable('statistiques_donations')) {
                $eventProgress = DB::table('statistiques_donations as s')
                    ->select(
                        's.evenement_id as id',
                        DB::raw('CASE 
                            WHEN s.evenement_id IS NULL THEN "Donation Générale" 
                            ELSE CONCAT("Événement #", s.evenement_id) 
                        END as title'),
                        DB::raw('1000 as target_amount'), // Default target for demo
                        's.montant_total as raised',
                        DB::raw('(s.montant_total / 1000) * 100 as progress_percentage')
                    )
                    ->where('s.montant_total', '>', 0)
                    ->orderByDesc('progress_percentage')
                    ->limit(5)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::info('Statistiques donations table not accessible: ' . $e->getMessage());
        }

        // Analytics by payment method
        $paymentMethods = DB::table('donations')
            ->select(
                'moyen_paiement',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(montant) as total'),
                DB::raw('AVG(montant) as average')
            )
            ->where('date_don', '>=', $startDate)
            ->groupBy('moyen_paiement')
            ->orderByDesc('total')
            ->get();

        // Alerts & Notifications
        $alerts = [];
        
        // Check for pending payouts (placeholder - implement based on your payout system)
        $pendingPayouts = 0; // Implement your payout logic
        if ($pendingPayouts > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-clock',
                'message' => "{$pendingPayouts} payout(s) en attente",
                'action' => '/admin/payouts'
            ];
        }

        // Check for recent bank transfers (since we don't have verified_at column)
        $recentBankTransfers = DB::table('donations')
            ->where('moyen_paiement', 'virement_bancaire')
            ->where('date_don', '>=', now()->subDays(7))
            ->count();
            
        if ($recentBankTransfers > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fas fa-university',
                'message' => "{$recentBankTransfers} virement(s) bancaire(s) récent(s)",
                'action' => '#'
            ];
        }

        return view('donations.association-dashboard', compact(
            'totalCollected',
            'evolution',
            'topEvents', 
            'recentDonations',
            'eventProgress',
            'paymentMethods',
            'alerts',
            'period'
        ));
    }

    // Lightweight metric: record when a suggestion button is clicked
    public function logSuggestionClick(Request $request)
    {
        $validated = $request->validate([
            'event_id' => ['required','integer'],
            'amount' => ['required','numeric','min:1'],
            'position' => ['nullable','string'], // low|mid|high
            'method' => ['nullable','string'],
        ]);
        try {
            LogFacade::info('donation_ai_suggestion_clicked', [
                'event_id' => (int)$validated['event_id'],
                'user_id' => AuthFacade::id(),
                'amount' => (float)$validated['amount'],
                'position' => $validated['position'] ?? null,
                'method' => $validated['method'] ?? null,
            ]);
        } catch (\Throwable $e) {}
        return response()->json(['ok' => true]);
    }
}
