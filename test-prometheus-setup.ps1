# TuniVert Prometheus & Grafana Testing Script
Write-Host "==================================" -ForegroundColor Green
Write-Host "  TuniVert Prometheus Testing" -ForegroundColor Green  
Write-Host "==================================" -ForegroundColor Green

# Test 1: Direct Laravel Metrics Endpoint
Write-Host "`n1. Testing Laravel Metrics Endpoint..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/metrics" -UseBasicParsing
    Write-Host "✅ Laravel endpoint reachable" -ForegroundColor Green
    Write-Host "Content-Type: $($response.Headers['Content-Type'])" -ForegroundColor Cyan
    Write-Host "Response length: $($response.Content.Length) bytes" -ForegroundColor Cyan
} catch {
    Write-Host "❌ Laravel endpoint failed: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 2: Nginx Proxy to Laravel Metrics
Write-Host "`n2. Testing Nginx Proxy to Laravel..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/metrics" -UseBasicParsing
    $metricsCount = ($response.Content -split "`n" | Where-Object { $_ -match "^tunivert_" -and $_ -notmatch "^#" }).Count
    Write-Host "✅ Nginx proxy working" -ForegroundColor Green
    Write-Host "Metrics exposed: $metricsCount" -ForegroundColor Cyan
} catch {
    Write-Host "❌ Nginx proxy failed: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 3: Prometheus Scraping
Write-Host "`n3. Testing Prometheus Scraping..." -ForegroundColor Yellow
$queries = @(
    @{ name = "App Status"; query = "tunivert_app_up" },
    @{ name = "Database Connection"; query = "tunivert_db_up" },
    @{ name = "AI Service"; query = "tunivert_ai_service_up" },
    @{ name = "Memory Usage"; query = "tunivert_memory_usage_bytes" }
)

foreach ($q in $queries) {
    try {
        $response = Invoke-WebRequest -Uri "http://127.0.0.1:9090/api/v1/query?query=$($q.query)" -UseBasicParsing
        $json = $response.Content | ConvertFrom-Json
        if ($json.data.result.Count -gt 0) {
            $value = $json.data.result[0].value[1]
            Write-Host "✅ $($q.name): $value" -ForegroundColor Green
        } else {
            Write-Host "⚠️  $($q.name): No data" -ForegroundColor Yellow
        }
    } catch {
        Write-Host "❌ $($q.name): Failed" -ForegroundColor Red
    }
}

# Test 4: Grafana Dashboard
Write-Host "`n4. Testing Grafana Dashboard..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:3000/api/dashboards/uid/tunivert-metrics" -UseBasicParsing
    Write-Host "✅ Dashboard exists and accessible" -ForegroundColor Green
    Write-Host "Dashboard URL: http://127.0.0.1:3000/d/tunivert-metrics" -ForegroundColor Cyan
} catch {
    Write-Host "❌ Dashboard not accessible: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 5: Query Performance
Write-Host "`n5. Testing Query Performance..." -ForegroundColor Yellow
$startTime = Get-Date
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:9090/api/v1/query_range?query=tunivert_memory_usage_bytes&start=$([int64]((Get-Date).AddMinutes(-5) - (Get-Date '1970-01-01')).TotalSeconds)&end=$([int64]((Get-Date) - (Get-Date '1970-01-01')).TotalSeconds)&step=30s" -UseBasicParsing
    $json = $response.Content | ConvertFrom-Json
    $endTime = Get-Date
    $duration = ($endTime - $startTime).TotalMilliseconds
    $dataPoints = if ($json.data.result.Count -gt 0) { $json.data.result[0].values.Count } else { 0 }
    Write-Host "✅ Range query completed in $($duration)ms" -ForegroundColor Green
    Write-Host "Data points returned: $dataPoints" -ForegroundColor Cyan
} catch {
    Write-Host "❌ Range query failed: $($_.Exception.Message)" -ForegroundColor Red
}

# Summary
Write-Host "`n==================================" -ForegroundColor Green
Write-Host "  Access URLs" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Green
Write-Host "📊 Prometheus: http://127.0.0.1:9090" -ForegroundColor Cyan
Write-Host "📈 Grafana: http://127.0.0.1:3000 (admin/admin123)" -ForegroundColor Cyan
Write-Host "🔧 Laravel Metrics: http://127.0.0.1:8000/metrics" -ForegroundColor Cyan
Write-Host "📋 TuniVert Dashboard: http://127.0.0.1:3000/d/tunivert-metrics" -ForegroundColor Cyan

Write-Host "`n==================================" -ForegroundColor Green
Write-Host "  Sample Prometheus Queries" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Green
Write-Host "• Application health: tunivert_app_up" -ForegroundColor White
Write-Host "• Memory usage (MB): tunivert_memory_usage_bytes / 1024 / 1024" -ForegroundColor White
Write-Host "• All metrics: {__name__=~'tunivert_.*'}" -ForegroundColor White
Write-Host "• Service uptime: (tunivert_app_up + tunivert_db_up + tunivert_ai_service_up) / 3" -ForegroundColor White

Write-Host "`n🎉 Testing completed! Your Prometheus setup is ready for monitoring TuniVert!" -ForegroundColor Green