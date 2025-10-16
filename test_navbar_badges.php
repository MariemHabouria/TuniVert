<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Badge Qualification Check ===\n\n";

// Check all users with email containing "example" (test users)
$users = DB::table('users')->where('email', 'like', '%example%')->get();

foreach ($users as $user) {
    echo "User: {$user->name} ({$user->email})\n";
    
    // Get donation totals
    $totalDonations = DB::table('donations')->where('utilisateur_id', $user->id)->sum('montant');
    $event2Donations = DB::table('donations')->where('utilisateur_id', $user->id)->where('evenement_id', 2)->sum('montant');
    
    echo "Total donations: {$totalDonations} TND\n";
    echo "Event 2 donations: {$event2Donations} TND\n";
    
    // Get badges in database
    $userBadges = DB::table('user_badges as ub')
        ->join('badges as b', 'b.id', '=', 'ub.badge_id')
        ->where('ub.user_id', $user->id)
        ->get(['b.slug', 'b.name', 'b.icon']);
    
    echo "Badges in database: " . $userBadges->count() . "\n";
    foreach ($userBadges as $badge) {
        echo "  - {$badge->slug}: {$badge->name} {$badge->icon}\n";
    }
    
    // Calculate which badges they should see (qualified for)
    $qualifiedBadges = [];
    foreach ($userBadges as $badge) {
        $qualified = false;
        switch($badge->slug) {
            case 'donor_bronze':
                $qualified = $totalDonations >= 50;
                break;
            case 'donor_silver':
                $qualified = $totalDonations >= 200;
                break;
            case 'donor_gold':
                $qualified = $totalDonations >= 500;
                break;
            case 'protector_oceans':
                $qualified = $event2Donations >= 100;
                break;
            default:
                $qualified = true;
        }
        
        if ($qualified) {
            $qualifiedBadges[] = $badge;
        }
    }
    
    echo "Should show in navbar: " . count($qualifiedBadges) . " badges\n";
    foreach ($qualifiedBadges as $badge) {
        echo "  ✓ {$badge->slug}: {$badge->name} {$badge->icon}\n";
    }
    
    echo "\n" . str_repeat('-', 50) . "\n\n";
}

echo "=== Instructions ===\n";
echo "1. Clear cache: php artisan cache:clear\n";
echo "2. Login and check profile dropdown\n";
echo "3. You should see only the qualified badges listed above\n";