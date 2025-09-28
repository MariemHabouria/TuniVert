<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use App\Services\GamificationService;
use Database\Seeders\GamificationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GamificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeDonation(User $user, float $amount, ?int $eventId = null, string $method = 'test', ?string $tx = null): Donation
    {
        return Donation::create([
            'utilisateur_id' => $user->id,
            'is_anonymous' => false,
            'evenement_id' => $eventId,
            'montant' => $amount,
            'moyen_paiement' => $method,
            'transaction_id' => $tx ?? 'tx_' . bin2hex(random_bytes(4)),
            'date_don' => now(),
        ]);
    }

    public function test_awards_points_for_donation(): void
    {
        $user = User::factory()->create();
        $service = app(GamificationService::class);

        $don = $this->makeDonation($user, 42.80, null, 'test');
        $service->onDonation($don);

        $sum = DB::table('points_ledger')->where('user_id', $user->id)->sum('points');
        // 1 point per 1 TND (rounded)
        $this->assertSame(43, (int)$sum);
    }

    public function test_awards_donor_badges_at_thresholds(): void
    {
        $this->seed(GamificationSeeder::class);
        $user = User::factory()->create();
        $service = app(GamificationService::class);

        // Reach >= 50 -> Bronze
        $service->onDonation($this->makeDonation($user, 50));
        $ownedSlugs = DB::table('user_badges as ub')
            ->join('badges as b', 'b.id', '=', 'ub.badge_id')
            ->where('ub.user_id', $user->id)
            ->pluck('b.slug')->all();
        $this->assertContains('donor_bronze', $ownedSlugs);

        // Reach >= 200 -> Silver
        $service->onDonation($this->makeDonation($user, 150));
        $ownedSlugs = DB::table('user_badges as ub')
            ->join('badges as b', 'b.id', '=', 'ub.badge_id')
            ->where('ub.user_id', $user->id)
            ->pluck('b.slug')->all();
        $this->assertContains('donor_silver', $ownedSlugs);

        // Reach >= 500 -> Gold
        $service->onDonation($this->makeDonation($user, 300));
        $ownedSlugs = DB::table('user_badges as ub')
            ->join('badges as b', 'b.id', '=', 'ub.badge_id')
            ->where('ub.user_id', $user->id)
            ->pluck('b.slug')->all();
        $this->assertContains('donor_gold', $ownedSlugs);
    }

    public function test_updates_monthly_challenge_progress_and_completion(): void
    {
        $this->seed(GamificationSeeder::class);
        $user = User::factory()->create();
        $service = app(GamificationService::class);

        // Seeder creates a monthly challenge scoped to event_id=2, target=1000, reward_points=100
        $service->onDonation($this->makeDonation($user, 600, 2));

        $part = DB::table('challenge_participations')->first();
        $this->assertNotNull($part);
        $this->assertEquals(600, (int)$part->progress_value);
        $this->assertNull($part->completed_at);

        // Push over target
        $service->onDonation($this->makeDonation($user, 500, 2));

        $part = DB::table('challenge_participations')->first();
        $this->assertNotNull($part);
        $this->assertGreaterThanOrEqual(1100, (int)$part->progress_value);
        $this->assertNotNull($part->completed_at);

        // Reward points should be awarded (100)
        $reasons = DB::table('points_ledger')->where('user_id', $user->id)->pluck('reason')->all();
        $this->assertContains('challenge_complete', $reasons);
        $bonus = DB::table('points_ledger')->where(['user_id'=>$user->id,'reason'=>'challenge_complete'])->sum('points');
        $this->assertEquals(100, (int)$bonus);
    }
}
