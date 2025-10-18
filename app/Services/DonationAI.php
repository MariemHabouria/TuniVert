<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DonationAI
{
    /**
     * Suggest donation amounts, propensity and method for a user×event.
     * Heuristic MVP; can be swapped to a real model later.
     */
    public function suggest(int $eventId, ?int $userId, array $ctx = []): array
    {
        $key = sprintf('donai:%s:%s', $userId ?? 'guest', $eventId);
        return Cache::remember($key, now()->addMinutes(10), function () use ($ctx, $eventId, $userId) {
            // Try external Python service first if configured
            $serviceUrl = config('services.donation_ai.url');
            if ($serviceUrl) {
                try {
                    $payload = [
                        'event_id' => $eventId,
                        'user_id' => $userId,
                        'ctx' => [
                            'user_total_tnd' => (float)($ctx['user_total_tnd'] ?? 0),
                            'event_popularity' => (int)($ctx['event_popularity'] ?? 0),
                            'event_days_left' => (int)($ctx['event_days_left'] ?? 30),
                            'default_method' => $ctx['default_method'] ?? null,
                        ],
                    ];
                    $resp = Http::timeout(2.0)->post(rtrim($serviceUrl, '/').'/suggest', $payload);
                    if ($resp->ok()) {
                        $data = $resp->json();
                        if (is_array($data) && isset($data['amounts'])) {
                            return [
                                'propensity' => (float)($data['propensity'] ?? 0.0),
                                'amounts' => [
                                    'low' => (int)($data['amounts']['low'] ?? 10),
                                    'mid' => (int)($data['amounts']['mid'] ?? 25),
                                    'high' => (int)($data['amounts']['high'] ?? 40),
                                ],
                                'method' => $data['method'] ?? ($ctx['default_method'] ?? 'paymee'),
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                    // Fall through to local heuristic
                }
            }

            // Local heuristic fallback
            $userTotal = (float)($ctx['user_total_tnd'] ?? 0);
            $pop = (int)($ctx['event_popularity'] ?? 0);
            $daysLeft = (int)($ctx['event_days_left'] ?? 30);
            $base = 0.15;
            $base += min($userTotal / 1000.0, 0.25);
            $base += min($pop / 100.0, 0.2);
            $base += $daysLeft < 7 ? 0.05 : 0.0;
            $base = min($base, 0.9);

            $mid = (int) round(max(10, min(250, ($userTotal > 0 ? $userTotal * 0.05 : 25))));
            $low = max(5, (int) round($mid * 0.6));
            $high = min(1000, (int) round($mid * 1.6));

            $method = $ctx['default_method'] ?? 'paymee';

            return [
                'propensity' => round($base, 3),
                'amounts' => ['low' => $low, 'mid' => $mid, 'high' => $high],
                'method' => $method,
            ];
        });
    }
}
