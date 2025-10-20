# PowerShell script to update donation statistics
# This script runs the command inside the Docker container where the database connection works

Write-Host "Updating donation statistics..." -ForegroundColor Green
docker exec tunivert_app php artisan donations:update-stats

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Statistics updated successfully!" -ForegroundColor Green
} else {
    Write-Host "❌ Error updating statistics. Make sure Docker is running." -ForegroundColor Red
}