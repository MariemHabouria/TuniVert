# TuniVert Database Migration and Seeding Script
Write-Host "🚀 Starting TuniVert Database Migration and Seeding..." -ForegroundColor Green

# Refresh the database (careful: this will drop all tables)
Write-Host "📊 Refreshing database..." -ForegroundColor Yellow
docker exec tunivert_app php artisan migrate:refresh

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migrations completed successfully!" -ForegroundColor Green
    
    # Run all seeders
    Write-Host "🌱 Running database seeders..." -ForegroundColor Yellow
    docker exec tunivert_app php artisan db:seed
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Seeding completed successfully!" -ForegroundColor Green
        
        # Verify the seeding was successful
        Write-Host "📋 Verification of seeded data..." -ForegroundColor Cyan
        
        Write-Host "Checking Users..." -ForegroundColor White
        docker exec tunivert_app php artisan tinker --execute="echo 'Users: ' . App\Models\User::count();"
        
        Write-Host "Checking Donations..." -ForegroundColor White
        docker exec tunivert_app php artisan tinker --execute="echo 'Donations: ' . App\Models\Donation::count();"
        
        Write-Host "Checking Events..." -ForegroundColor White
        docker exec tunivert_app php artisan tinker --execute="echo 'Events: ' . App\Models\Event::count();"
        
        Write-Host "Checking Challenges..." -ForegroundColor White
        docker exec tunivert_app php artisan tinker --execute="echo 'Challenges: ' . App\Models\Challenge::count();"
        
        Write-Host "Checking Forums..." -ForegroundColor White
        docker exec tunivert_app php artisan tinker --execute="echo 'Forums: ' . App\Models\Forum::count();"
        
        Write-Host "🎉 Database migration and seeding completed successfully!" -ForegroundColor Green
    } else {
        Write-Host "❌ Seeding failed!" -ForegroundColor Red
    }
} else {
    Write-Host "❌ Migration failed!" -ForegroundColor Red
}