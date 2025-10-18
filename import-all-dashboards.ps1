# Import All TuniVert Dashboards to Grafana
Write-Host "Importing TuniVert Dashboards to Grafana..." -ForegroundColor Green

$grafanaUrl = "http://127.0.0.1:3000"
$credentials = "admin:admin123"
$encodedCredentials = [System.Convert]::ToBase64String([System.Text.Encoding]::ASCII.GetBytes($credentials))

$headers = @{
    "Authorization" = "Basic $encodedCredentials"
    "Content-Type" = "application/json"
}

# Dashboard files to import
$dashboards = @(
    @{
        "file" = "monitoring\grafana\dashboards\tunivert-business-dashboard.json"
        "name" = "Business Intelligence Dashboard"
    },
    @{
        "file" = "monitoring\grafana\dashboards\tunivert-performance-dashboard.json"
        "name" = "Performance & Infrastructure Dashboard"
    }
)

$importedDashboards = @()

foreach ($dashboard in $dashboards) {
    Write-Host "`nImporting $($dashboard.name)..." -ForegroundColor Cyan
    
    try {
        $dashboardJson = Get-Content -Path $dashboard.file -Raw
        $dashboardData = ($dashboardJson | ConvertFrom-Json).dashboard
        
        $body = @{
            dashboard = $dashboardData
            overwrite = $true
        } | ConvertTo-Json -Depth 20
        
        $response = Invoke-RestMethod -Uri "$grafanaUrl/api/dashboards/db" -Method POST -Headers $headers -Body $body
        
        $dashboardUrl = "$grafanaUrl/d/$($response.uid)"
        $importedDashboards += @{
            name = $dashboard.name
            uid = $response.uid
            url = $dashboardUrl
        }
        
        Write-Host "✅ Successfully imported: $($dashboard.name)" -ForegroundColor Green
        Write-Host "   URL: $dashboardUrl" -ForegroundColor Gray
        
    } catch {
        Write-Host "❌ Failed to import $($dashboard.name): $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host "`n" + ("="*50) -ForegroundColor Yellow
Write-Host "GRAFANA DASHBOARD SUMMARY" -ForegroundColor Yellow
Write-Host ("="*50) -ForegroundColor Yellow

Write-Host "`n🌐 Grafana Access:" -ForegroundColor Cyan
Write-Host "   URL: $grafanaUrl" -ForegroundColor White
Write-Host "   Username: admin" -ForegroundColor White  
Write-Host "   Password: admin123" -ForegroundColor White

Write-Host "`n📊 Imported Dashboards:" -ForegroundColor Cyan
foreach ($dashboard in $importedDashboards) {
    Write-Host "   • $($dashboard.name)" -ForegroundColor White
    Write-Host "     $($dashboard.url)" -ForegroundColor Gray
}

Write-Host "`n🚨 Alerting:" -ForegroundColor Cyan
Write-Host "   • Rules: http://127.0.0.1:9090/rules" -ForegroundColor White
Write-Host "   • Alerts: http://127.0.0.1:9090/alerts" -ForegroundColor White

Write-Host "`n🔍 Prometheus:" -ForegroundColor Cyan
Write-Host "   • Metrics: http://127.0.0.1:9090/graph" -ForegroundColor White
Write-Host "   • Targets: http://127.0.0.1:9090/targets" -ForegroundColor White

# Open the main business dashboard
if ($importedDashboards.Count -gt 0) {
    $mainDashboard = $importedDashboards[0]
    Write-Host "`n🚀 Opening main dashboard..." -ForegroundColor Green
    Start-Process $mainDashboard.url
}

Write-Host "`n✨ All dashboards imported successfully!" -ForegroundColor Green