<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('donations')) { return; }
        // Only applicable to MySQL/MariaDB; skip for sqlite/pgsql/sqlsrv
        $driver = DB::getDriverName();
        if (!in_array($driver, ['mysql','mariadb'])) {
            return;
        }
        // Expand ENUM to include 'paymee' and 'test'
        DB::statement("ALTER TABLE `donations` MODIFY `moyen_paiement` ENUM('carte','paypal','virement_bancaire','paymee','test') NOT NULL");
    }

    public function down(): void
    {
        if (!Schema::hasTable('donations')) { return; }
        $driver = DB::getDriverName();
        if (!in_array($driver, ['mysql','mariadb'])) {
            return;
        }
        // Revert to original set (may fail if rows exist with removed values)
        DB::statement("ALTER TABLE `donations` MODIFY `moyen_paiement` ENUM('carte','paypal','virement_bancaire') NOT NULL");
    }
};
