#!/bin/bash

echo "🚀 Starting TuniVert Database Migration and Seeding..."

# Refresh the database (careful: this will drop all tables)
echo "📊 Refreshing database..."
php artisan migrate:refresh

# Run all seeders
echo "🌱 Running database seeders..."
php artisan db:seed

# Verify the seeding was successful
echo "✅ Verification of seeded data..."
php artisan tinker --execute="
echo 'Users: ' . App\Models\User::count() . PHP_EOL;
echo 'Donations: ' . App\Models\Donation::count() . PHP_EOL;
echo 'Events: ' . App\Models\Event::count() . PHP_EOL;
echo 'Challenges: ' . App\Models\Challenge::count() . PHP_EOL;
echo 'Forums: ' . App\Models\Forum::count() . PHP_EOL;
echo 'Badges: ' . App\Models\Badge::count() . PHP_EOL;
"

echo "🎉 Database migration and seeding completed successfully!"