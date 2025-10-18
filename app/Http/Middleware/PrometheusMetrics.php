<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMetrics
{
    public function handle(Request $request, Closure $next): Response
    {
        // Increment request counter
        $requestCount = Cache::get('http_requests_total', 0);
        Cache::put('http_requests_total', $requestCount + 1);
        
        $response = $next($request);
        
        // Track errors (4xx, 5xx)
        if ($response->getStatusCode() >= 400) {
            $errorCount = Cache::get('http_errors_total', 0);
            Cache::put('http_errors_total', $errorCount + 1);
        }
        
        return $response;
    }
}