<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\GamificationService;

class TestPaymentController extends Controller
{
    private function isEnabled(): bool
    {
        return (bool) config('services.testpay.enabled', false);
    }

    // Initialize a mock payment and redirect to a local checkout page
    public function create(Request $request)
    {
        if (!$this->isEnabled()) {
            return response()->json(['error' => 'Test payments are disabled'], 403);
        }
        $validated = $request->validate([
            'amount' => ['required','numeric','min:1'],
            'evenement_id' => ['nullable','integer'],
        ]);
        $token = 'test_' . bin2hex(random_bytes(8));
        $payload = [
            'user_id' => Auth::id(),
            'amount' => (float) $validated['amount'],
            'evenement_id' => $validated['evenement_id'] ?? null,
            'created_at' => time(),
        ];
        // Store in session for the short flow
        session(["testpay.$token" => $payload]);
        $redirectUrl = route('payments.test.checkout', ['t' => $token]);
        return response()->json(['redirect_url' => $redirectUrl, 'token' => $token]);
    }

    // Show a simple checkout form (amount + mock e-dinar code)
    public function checkout(Request $request)
    {
        if (!$this->isEnabled()) { abort(404); }
        $token = (string) $request->query('t');
        $data = session("testpay.$token");
        if (!$token || !$data || ($data['user_id'] ?? null) !== Auth::id()) {
            return redirect()->route('donations.history')->with('error', 'Invalid or expired test session.');
        }
        return view('payments.test-checkout', [
            'token' => $token,
            'amount' => $data['amount'] ?? 0,
            'evenement_id' => $data['evenement_id'] ?? null,
        ]);
    }

    // Complete the mock payment and create a Donation
    public function complete(Request $request)
    {
        if (!$this->isEnabled()) { abort(404); }
        $validated = $request->validate([
            'token' => ['required','string'],
            // Mock e‑DINAR fields; basic constraints for UX only
            'card_number' => ['required','string','min:12','max:22'],
            'pin' => ['required','string','min:4','max:6'],
            'cin' => ['required','string','min:6','max:12'],
        ]);
        $token = $validated['token'];
        $data = session("testpay.$token");
        if (!$data || ($data['user_id'] ?? null) !== Auth::id()) {
            return redirect()->route('donations.history')->with('error', 'Invalid or expired test session.');
        }
        // Simulate a successful charge
        $don = Donation::create([
            'utilisateur_id' => Auth::id(),
            'is_anonymous' => (bool)$request->boolean('is_anonymous'),
            'evenement_id' => $data['evenement_id'] ?? null,
            'montant' => (float)$data['amount'],
            'moyen_paiement' => 'test',
            'transaction_id' => $token,
            'date_don' => now(),
        ]);
    // Gamification
    try {
        $res = app(GamificationService::class)->onDonation($don);
        if (!empty($res['new_badges'])) {
            session()->flash('new_badges', $res['new_badges']);
        }
    } catch (\Throwable $e) {}
    // Flash points for history page
    session()->flash('points_earned', (int) round((float)$don->montant));
    // Clear session for this token
        session()->forget("testpay.$token");
        try {
            $owner = $don->user ?? Auth::user();
            if ($owner) {
                $owner->notify(new \App\Notifications\DonationReceived($don));
                $recipient = $owner->email ?? null;
                if ($recipient) { 
                    // Use simple version with QR code in attachment only
                    Mail::to($recipient)->queue(new \App\Mail\DonationReceiptSimple($don)); 
                }
            }
        } catch (\Throwable $e) { Log::debug('TestPay notify failed: '.$e->getMessage()); }
        return redirect()->route('donations.history')->with('status', 'Test payment completed successfully. Thank you!');
    }

    public function cancel(Request $request)
    {
        if (!$this->isEnabled()) { abort(404); }
        $token = (string) $request->query('t');
        if ($token) { session()->forget("testpay.$token"); }
        return redirect()->route('donations.history')->with('error', 'Test payment cancelled.');
    }
}
