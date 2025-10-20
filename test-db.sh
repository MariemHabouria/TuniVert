#!/bin/bash
echo "=== TuniVert Database Connection Test ==="
docker exec tunivert_app php -r "
require '/var/www/html/vendor/autoload.php';
\$app = require '/var/www/html/bootstrap/app.php';
\$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    \$count = \Illuminate\Support\Facades\DB::table('users')->count();
    echo 'Success! Found ' . \$count . ' users.' . PHP_EOL;
    
    \$user = \Illuminate\Support\Facades\DB::table('users')->where('email', 'admin@tunivert.tn')->first();
    if (\$user) {
        echo 'Admin user: ' . \$user->name . PHP_EOL;
        echo 'Role: ' . \$user->role . PHP_EOL;
        echo 'Blocked: ' . (\$user->is_blocked ?? 0) . PHP_EOL;
    } else {
        echo 'Admin user NOT found!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"