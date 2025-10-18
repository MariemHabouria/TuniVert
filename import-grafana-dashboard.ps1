# Import TuniVert Dashboard to Grafana
Write-Host "Importing TuniVert Dashboard to Grafana..." -ForegroundColor Green

$dashboardJson = Get-Content -Path "monitoring\grafana\dashboards\tunivert-metrics.json" -Raw
$grafanaUrl = "http://127.0.0.1:3000"
$credentials = "admin:admin123"
$encodedCredentials = [System.Convert]::ToBase64String([System.Text.Encoding]::ASCII.GetBytes($credentials))

$headers = @{
    "Authorization" = "Basic $encodedCredentials"
    "Content-Type" = "application/json"
}

$body = @{
    dashboard = ($dashboardJson | ConvertFrom-Json).dashboard
    overwrite = $true
} | ConvertTo-Json -Depth 10

try {
    $response = Invoke-RestMethod -Uri "$grafanaUrl/api/dashboards/db" -Method POST -Headers $headers -Body $body
    Write-Host "Dashboard imported successfully!" -ForegroundColor Green
    Write-Host "Dashboard URL: $grafanaUrl/d/$($response.uid)" -ForegroundColor Cyan
    
    # Open the dashboard in browser
    Start-Process "$grafanaUrl/d/$($response.uid)"
    
} catch {
    Write-Host "Failed to import dashboard: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Response: $($_.Exception.Response)" -ForegroundColor Yellow
}

Write-Host "`nGrafana Credentials:" -ForegroundColor Yellow
Write-Host "URL: http://127.0.0.1:3000" -ForegroundColor Cyan
Write-Host "Username: admin" -ForegroundColor Cyan
Write-Host "Password: admin123" -ForegroundColor Cyan