<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class GamificationService
{
    /**
     * Donation-related badge slugs managed by this service
     */
    protected array $donationBadgeSlugs = [
        'donor_bronze',
        'donor_silver',
        'donor_gold',
        'protector_oceans',
    ];

    public function onDonation(Donation $donation): array
    {
        $user = $donation->user;
        if (!$user) return ['points' => 0, 'new_badges' => []];

        $points = (int) round((float)$donation->montant); // 1 point per 1 TND
        $this->awardPoints($user, $points, 'donation', $donation, [
            'amount' => (float)$donation->montant,
            'event' => $donation->evenement_id,
            'method' => $donation->moyen_paiement,
        ]);

        // Keep donation badges in sync after each donation
        $sync = $this->syncDonationBadges($user);
        $newBadges = $sync['awarded'] ?? [];
        $this->updateChallenges($user, $donation);
        return ['points' => $points, 'new_badges' => $newBadges];
    }

    public function awardPoints(User $user, int $points, string $reason, ?Donation $donation = null, array $meta = []): void
    {
        DB::table('points_ledger')->insert([
            'user_id' => $user->id,
            'points' => $points,
            'reason' => $reason,
            'donation_id' => $donation?->id,
            'metadata' => $meta ? json_encode($meta) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function evaluateBadges(User $user): array
    {
        // Backward-compatible helper: only adds missing donation badges
        $totals = DB::table('donations')
            ->selectRaw('COALESCE(SUM(montant),0) as total_amount')
            ->where('utilisateur_id', $user->id)
            ->first();

        $event2 = DB::table('donations')
            ->selectRaw('COALESCE(SUM(montant),0) as total_amount')
            ->where('utilisateur_id', $user->id)
            ->where('evenement_id', 2)
            ->first();

        $rules = [
            'donor_bronze' => fn() => ($totals->total_amount ?? 0) >= 50,
            'donor_silver' => fn() => ($totals->total_amount ?? 0) >= 200,
            'donor_gold'   => fn() => ($totals->total_amount ?? 0) >= 500,
            'protector_oceans' => fn() => ($event2->total_amount ?? 0) >= 100,
        ];

        $badgeMap = DB::table('badges')->pluck('id', 'slug');
        $badgeInfo = DB::table('badges')->select('id','slug','name','icon','description')->get()->keyBy('slug');
        $owned = DB::table('user_badges')->where('user_id', $user->id)->pluck('badge_id');
        $ownedSlugs = DB::table('badges')->whereIn('id', $owned)->pluck('slug')->all();
        $awarded = [];

        foreach ($rules as $slug => $ok) {
            if (!in_array($slug, $ownedSlugs, true) && $ok() && isset($badgeMap[$slug])) {
                DB::table('user_badges')->insert([
                    'user_id' => $user->id,
                    'badge_id' => $badgeMap[$slug],
                    'awarded_at' => now(),
                ]);
                if (isset($badgeInfo[$slug])) {
                    $b = $badgeInfo[$slug];
                    $awarded[] = [
                        'slug' => $b->slug,
                        'name' => $b->name,
                        'icon' => $b->icon,
                        'description' => $b->description,
                    ];
                } else {
                    $awarded[] = ['slug'=>$slug];
                }
            }
        }
        return $awarded;
    }

    /**
     * Synchronize donation-related badges: add missing ones that meet rules and remove those no longer valid.
     * Returns an array with keys 'awarded' and 'removed' (removed contains slugs).
     */
    public function syncDonationBadges(User $user): array
    {
        // Compute totals for rules
        $totals = DB::table('donations')
            ->selectRaw('COALESCE(SUM(montant),0) as total_amount')
            ->where('utilisateur_id', $user->id)
            ->first();

        $event2 = DB::table('donations')
            ->selectRaw('COALESCE(SUM(montant),0) as total_amount')
            ->where('utilisateur_id', $user->id)
            ->where('evenement_id', 2)
            ->first();

        $rules = [
            'donor_bronze' => fn() => ($totals->total_amount ?? 0) >= 50,
            'donor_silver' => fn() => ($totals->total_amount ?? 0) >= 200,
            'donor_gold'   => fn() => ($totals->total_amount ?? 0) >= 500,
            'protector_oceans' => fn() => ($event2->total_amount ?? 0) >= 100,
        ];

        $badgeMap = DB::table('badges')->whereIn('slug', $this->donationBadgeSlugs)->pluck('id', 'slug');
        $badgeInfo = DB::table('badges')->select('id','slug','name','icon','description')->get()->keyBy('slug');

        $ownedIds = DB::table('user_badges')->where('user_id', $user->id)->pluck('badge_id');
        $ownedSlugs = DB::table('badges')->whereIn('id', $ownedIds)->whereIn('slug', $this->donationBadgeSlugs)->pluck('slug')->all();

        $shouldHave = [];
        foreach ($rules as $slug => $ok) {
            if ($ok()) $shouldHave[] = $slug;
        }

        $toAdd = array_values(array_diff($shouldHave, $ownedSlugs));
        $toRemove = array_values(array_diff($ownedSlugs, $shouldHave));

        $awarded = [];
        foreach ($toAdd as $slug) {
            if (!isset($badgeMap[$slug])) continue;
            DB::table('user_badges')->insert([
                'user_id' => $user->id,
                'badge_id' => $badgeMap[$slug],
                'awarded_at' => now(),
            ]);
            if (isset($badgeInfo[$slug])) {
                $b = $badgeInfo[$slug];
                $awarded[] = [
                    'slug' => $b->slug,
                    'name' => $b->name,
                    'icon' => $b->icon,
                    'description' => $b->description,
                ];
            } else {
                $awarded[] = ['slug'=>$slug];
            }
        }

        if (!empty($toRemove)) {
            $idsToRemove = DB::table('badges')->whereIn('slug', $toRemove)->pluck('id');
            DB::table('user_badges')->where('user_id', $user->id)->whereIn('badge_id', $idsToRemove)->delete();
        }

        return ['awarded' => $awarded, 'removed' => $toRemove];
    }

    /**
     * Remove all donation-related badges if the user has no donation history.
     * Returns number of badges removed.
     */
    public function revokeDonationBadgesIfNoDonations(User $user): int
    {
        $hasDonations = DB::table('donations')->where('utilisateur_id', $user->id)->exists();
        if ($hasDonations) return 0;
        $ids = DB::table('badges')->whereIn('slug', $this->donationBadgeSlugs)->pluck('id');
        return DB::table('user_badges')->where('user_id', $user->id)->whereIn('badge_id', $ids)->delete();
    }

    public function updateChallenges(User $user, Donation $donation): void
    {
        $now = now();
        $active = DB::table('challenges')
            ->where('period_start', '<=', $now)
            ->where('period_end', '>=', $now)
            ->get();

        foreach ($active as $ch) {
            if (!$this->matchesScope($ch, $donation)) continue;

            $delta = match ($ch->metric) {
                'amount_tnd' => (int) round((float)$donation->montant),
                'donations_count' => 1,
                default => 0,
            };

            $part = DB::table('challenge_participations')
                ->where('challenge_id', $ch->id)
                ->where('user_id', $user->id)
                ->first();

            if ($part) {
                $new = min(PHP_INT_MAX, (int)$part->progress_value + $delta);
                DB::table('challenge_participations')
                    ->where('id', $part->id)
                    ->update(['progress_value' => $new, 'updated_at' => now()]);
                $completed = $part->completed_at ? true : ($new >= (int)$ch->target_value);
            } else {
                $new = $delta;
                DB::table('challenge_participations')->insert([
                    'challenge_id' => $ch->id,
                    'user_id' => $user->id,
                    'progress_value' => $new,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $completed = $new >= (int)$ch->target_value;
            }

            if ($completed) {
                DB::table('challenge_participations')
                    ->where('challenge_id', $ch->id)
                    ->where('user_id', $user->id)
                    ->whereNull('completed_at')
                    ->update(['completed_at' => now(), 'updated_at' => now()]);

                if ((int)$ch->reward_points > 0) {
                    $this->awardPoints($user, (int)$ch->reward_points, 'challenge_complete', $donation, ['challenge' => $ch->slug]);
                }
                if ($ch->badge_id) {
                    $has = DB::table('user_badges')->where(['user_id'=>$user->id,'badge_id'=>$ch->badge_id])->exists();
                    if (!$has) {
                        DB::table('user_badges')->insert([
                            'user_id'=>$user->id,'badge_id'=>$ch->badge_id,'awarded_at'=>now()
                        ]);
                    }
                }
            }
        }
    }

    protected function matchesScope($challenge, Donation $donation): bool
    {
        return match ($challenge->scope_type) {
            'global' => true,
            'event' => (string)($donation->evenement_id ?? '') === (string)$challenge->scope_value,
            'campaign' => request('campaign') === $challenge->scope_value,
            default => false,
        };
    }
}