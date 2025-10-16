<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use App\Models\Donation;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Badge Removal Logic\n";
echo "===========================\n";

// Find test user
$user = User::first();
if (!$user) {
    echo "No users found in database. Please create a user first.\n";
    exit(1);
}

echo "Test User: {$user->name} ({$user->email})\n";

// Clear existing donations and badges for clean test
DB::table('donations')->where('utilisateur_id', $user->id)->delete();
DB::table('user_badges')->where('user_id', $user->id)->delete();
DB::table('points_ledger')->where('user_id', $user->id)->delete();

echo "✅ Cleared existing data for clean test\n\n";

$gamificationService = app(GamificationService::class);

// Test 1: Create donation for Bronze badge (50 TND)
echo "🧪 Test 1: Donate 60 TND (should earn Bronze badge)\n";
$donation1 = Donation::create([
    'utilisateur_id' => $user->id,
    'montant' => 60.00,
    'moyen_paiement' => 'virement_bancaire',
    'transaction_id' => 'TEST_BRONZE_' . time(),
    'date_don' => now(),
    'is_anonymous' => false
]);

$result1 = $gamificationService->onDonation($donation1);
echo "New badges: " . json_encode($result1['new_badges'] ?? []) . "\n";

// Check current badges
$badges = DB::table('user_badges as ub')
    ->join('badges as b', 'b.id', '=', 'ub.badge_id')
    ->where('ub.user_id', $user->id)
    ->pluck('b.slug')
    ->toArray();
echo "Current badges: " . implode(', ', $badges) . "\n";
echo "Total donations: " . DB::table('donations')->where('utilisateur_id', $user->id)->sum('montant') . " TND\n\n";

// Test 2: Add more for Silver badge (200 TND total)
echo "🧪 Test 2: Donate additional 150 TND (total 210 TND - should earn Silver)\n";
$donation2 = Donation::create([
    'utilisateur_id' => $user->id,
    'montant' => 150.00,
    'moyen_paiement' => 'carte',
    'transaction_id' => 'TEST_SILVER_' . time(),
    'date_don' => now(),
    'is_anonymous' => false
]);

$result2 = $gamificationService->onDonation($donation2);
echo "New badges: " . json_encode($result2['new_badges'] ?? []) . "\n";

$badges = DB::table('user_badges as ub')
    ->join('badges as b', 'b.id', '=', 'ub.badge_id')
    ->where('ub.user_id', $user->id)
    ->pluck('b.slug')
    ->toArray();
echo "Current badges: " . implode(', ', $badges) . "\n";
echo "Total donations: " . DB::table('donations')->where('utilisateur_id', $user->id)->sum('montant') . " TND\n\n";

// Test 3: Remove some donations to test badge removal
echo "🧪 Test 3: Remove first donation (back to 150 TND - should lose Silver, keep Bronze)\n";
$donation1->delete();

// Re-evaluate badges
$badgeResult = $gamificationService->evaluateBadges($user);
echo "Badge evaluation result: " . json_encode($badgeResult) . "\n";

$badges = DB::table('user_badges as ub')
    ->join('badges as b', 'b.id', '=', 'ub.badge_id')
    ->where('ub.user_id', $user->id)
    ->pluck('b.slug')
    ->toArray();
echo "Current badges: " . implode(', ', $badges) . "\n";
echo "Total donations: " . DB::table('donations')->where('utilisateur_id', $user->id)->sum('montant') . " TND\n\n";

// Test 4: Remove all donations (should lose all badges)
echo "🧪 Test 4: Remove all donations (0 TND - should lose all badges)\n";
DB::table('donations')->where('utilisateur_id', $user->id)->delete();

$badgeResult = $gamificationService->evaluateBadges($user);
echo "Badge evaluation result: " . json_encode($badgeResult) . "\n";

$badges = DB::table('user_badges as ub')
    ->join('badges as b', 'b.id', '=', 'ub.badge_id')
    ->where('ub.user_id', $user->id)
    ->pluck('b.slug')
    ->toArray();
echo "Current badges: " . implode(', ', $badges) . "\n";
echo "Total donations: " . DB::table('donations')->where('utilisateur_id', $user->id)->sum('montant') . " TND\n\n";

echo "🎉 Badge removal testing complete!\n";
echo "\nBadge Thresholds:\n";
echo "- Bronze: ≥50 TND\n";  
echo "- Silver: ≥200 TND\n";
echo "- Gold: ≥500 TND\n";
echo "- Ocean Protector: ≥100 TND on Event 2\n";