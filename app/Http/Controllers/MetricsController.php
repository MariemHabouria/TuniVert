<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Donation;
use App\Models\User;
use App\Models\Event;

class MetricsController extends Controller
{
    public function index()
    {
        $metrics = [];
        
        try {
            // Application uptime metric
            $metrics[] = $this->formatMetric('tunivert_app_up', 'gauge', 'Application is running', 1);
            
            // Current timestamp
            $metrics[] = $this->formatMetric('tunivert_timestamp', 'gauge', 'Current timestamp', time());
            
            // Application version
            $metrics[] = $this->formatMetric('tunivert_version_info', 'gauge', 'Application version info', 1, ['version' => '1.0.0']);
            
            // Database connection health
            $dbHealth = $this->checkDatabaseHealth();
            $metrics[] = $this->formatMetric('tunivert_db_up', 'gauge', 'Database connectivity', $dbHealth ? 1 : 0);
            
            // Real business metrics - with error handling
            if ($dbHealth) {
                try {
                    // User metrics
                    $totalUsers = User::count();
                    $metrics[] = $this->formatMetric('tunivert_users_total', 'counter', 'Total registered users', $totalUsers);
                    
                    $recentUsers = User::where('created_at', '>=', Carbon::now()->subDay())->count();
                    $metrics[] = $this->formatMetric('tunivert_users_24h', 'gauge', 'New users in last 24 hours', $recentUsers);
                    
                    // Count users by role
                    $adminUsers = User::where('role', 'admin')->count();
                    $associationUsers = User::where('role', 'association')->count();
                    $regularUsers = User::where('role', 'user')->count();
                    
                    $metrics[] = $this->formatMetric('tunivert_users_by_role', 'counter', 'Users by role', $adminUsers, ['role' => 'admin']);
                    $metrics[] = $this->formatMetric('tunivert_users_by_role', 'counter', 'Users by role', $associationUsers, ['role' => 'association']);
                    $metrics[] = $this->formatMetric('tunivert_users_by_role', 'counter', 'Users by role', $regularUsers, ['role' => 'user']);
                    
                    // Event metrics
                    $totalEvents = Event::count();
                    $metrics[] = $this->formatMetric('tunivert_events_total', 'counter', 'Total events created', $totalEvents);
                    
                    // Note: Events table has 'date' column, not date_debut/date_fin
                    // For now, just count all events as we can't determine "active" status
                    $todayEvents = Event::whereDate('date', Carbon::now()->toDateString())->count();
                    $metrics[] = $this->formatMetric('tunivert_events_today', 'gauge', 'Events happening today', $todayEvents);
                    
                    // Donation metrics
                    $totalDonations = Donation::count();
                    $totalAmount = Donation::sum('montant') ?? 0;
                    $avgDonation = $totalDonations > 0 ? $totalAmount / $totalDonations : 0;
                    
                    $metrics[] = $this->formatMetric('tunivert_donations_total', 'counter', 'Total donations', $totalDonations);
                    $metrics[] = $this->formatMetric('tunivert_donation_amount_total_tnd', 'counter', 'Total donation amount in TND', $totalAmount);
                    $metrics[] = $this->formatMetric('tunivert_donation_amount_avg_tnd', 'gauge', 'Average donation amount', round($avgDonation, 2));
                    
                    // Donations by payment method
                    $paymentMethods = Donation::select('moyen_paiement', \DB::raw('count(*) as total'))
                        ->groupBy('moyen_paiement')
                        ->get();
                    
                    foreach ($paymentMethods as $method) {
                        $methodName = $method->moyen_paiement ?? 'unknown';
                        $metrics[] = $this->formatMetric(
                            'tunivert_donations_by_method', 
                            'counter', 
                            'Donations by payment method', 
                            $method->total,
                            ['method' => $methodName]
                        );
                    }
                    
                    // Recent activity (last 24h)
                    $recentDonations = Donation::where('date_don', '>=', Carbon::now()->subDay())->count();
                    $recentDonationAmount = Donation::where('date_don', '>=', Carbon::now()->subDay())->sum('montant') ?? 0;
                    
                    $metrics[] = $this->formatMetric('tunivert_donations_24h', 'gauge', 'Donations in last 24 hours', $recentDonations);
                    $metrics[] = $this->formatMetric('tunivert_donation_amount_24h_tnd', 'gauge', 'Donation amount in last 24 hours', $recentDonationAmount);
                    
                    // Weekly metrics
                    $weeklyDonations = Donation::where('date_don', '>=', Carbon::now()->subWeek())->count();
                    $weeklyAmount = Donation::where('date_don', '>=', Carbon::now()->subWeek())->sum('montant') ?? 0;
                    
                    $metrics[] = $this->formatMetric('tunivert_donations_7d', 'gauge', 'Donations in last 7 days', $weeklyDonations);
                    $metrics[] = $this->formatMetric('tunivert_donation_amount_7d_tnd', 'gauge', 'Donation amount in last 7 days', $weeklyAmount);
                    
                    // Success rate metrics
                    $successfulDonations = Donation::whereNotNull('transaction_id')->count();
                    $successRate = $totalDonations > 0 ? ($successfulDonations / $totalDonations) * 100 : 100;
                    $metrics[] = $this->formatMetric('tunivert_donation_success_rate_percent', 'gauge', 'Donation success rate percentage', round($successRate, 2));
                    
                    // Anonymous vs named donations
                    $anonymousDonations = Donation::where('is_anonymous', true)->count();
                    $namedDonations = $totalDonations - $anonymousDonations;
                    $metrics[] = $this->formatMetric('tunivert_donations_anonymous', 'counter', 'Anonymous donations', $anonymousDonations);
                    $metrics[] = $this->formatMetric('tunivert_donations_named', 'counter', 'Named donations', $namedDonations);
                    
                } catch (\Exception $dbError) {
                    // If database queries fail, track the error but continue with other metrics
                    $metrics[] = $this->formatMetric('tunivert_db_query_errors', 'counter', 'Database query errors', 1);
                    Log::warning('Database metrics collection failed: ' . $dbError->getMessage());
                }
            }
            
            // AI Service health check
            $aiServiceHealth = $this->checkAIServiceHealth();
            $metrics[] = $this->formatMetric('tunivert_ai_service_up', 'gauge', 'AI service availability', $aiServiceHealth ? 1 : 0);
            
            // System metrics
            $memoryUsage = memory_get_usage(true);
            $metrics[] = $this->formatMetric('tunivert_memory_usage_bytes', 'gauge', 'Memory usage in bytes', $memoryUsage);
            
            // Request processing time
            $processTime = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
            $metrics[] = $this->formatMetric('tunivert_request_duration_seconds', 'gauge', 'Request processing time', round($processTime, 4));
            
            // HTTP request metrics (from cache/session)
            $httpRequests = \Cache::get('http_requests_total', 0);
            $httpErrors = \Cache::get('http_errors_total', 0);
            $metrics[] = $this->formatMetric('tunivert_http_requests_total', 'counter', 'Total HTTP requests', $httpRequests);
            $metrics[] = $this->formatMetric('tunivert_http_errors_total', 'counter', 'Total HTTP errors', $httpErrors);
            
        } catch (\Exception $e) {
            $metrics[] = $this->formatMetric('tunivert_metrics_error', 'gauge', 'Metrics collection errors', 1);
            Log::error('Metrics collection failed: ' . $e->getMessage());
        }
        
        $content = implode("\n", $metrics) . "\n";
        
        return response($content, 200, [
            'Content-Type' => 'text/plain; version=0.0.4; charset=utf-8'
        ]);
    }
    
    private function formatMetric(string $name, string $type, string $help, $value, array $labels = []): string
    {
        $output = "# HELP {$name} {$help}\n";
        $output .= "# TYPE {$name} {$type}\n";
        
        if (empty($labels)) {
            $output .= "{$name} {$value}\n";
        } else {
            $labelStr = implode(',', array_map(fn($k, $v) => "{$k}=\"{$v}\"", array_keys($labels), $labels));
            $output .= "{$name}{{$labelStr}} {$value}\n";
        }
        
        return $output;
    }
    
    private function checkAIServiceHealth(): bool
    {
        try {
            $url = config('services.donation_ai.url');
            if (!$url) return false;
            
            $response = Http::timeout(5)->get("{$url}/health");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function checkDatabaseHealth(): bool
    {
        try {
            // Use a simple connection test
            $connection = \DB::connection();
            $connection->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}