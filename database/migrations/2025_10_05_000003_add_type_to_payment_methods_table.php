<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('type', 50)->nullable()->after('key');
            $table->string('color_primary', 20)->default('#007bff')->after('icon_path');
            $table->string('color_secondary', 20)->default('#ffffff')->after('color_primary');
            $table->string('button_text', 100)->default('Pay now')->after('color_secondary');
            $table->text('custom_form_fields')->nullable()->after('button_text');
            $table->text('custom_css')->nullable()->after('custom_form_fields');
            $table->text('instructions_html')->nullable()->after('custom_css');
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (Schema::hasColumn('payment_methods', 'instructions_html')) $table->dropColumn('instructions_html');
            if (Schema::hasColumn('payment_methods', 'custom_css')) $table->dropColumn('custom_css');
            if (Schema::hasColumn('payment_methods', 'custom_form_fields')) $table->dropColumn('custom_form_fields');
            if (Schema::hasColumn('payment_methods', 'button_text')) $table->dropColumn('button_text');
            if (Schema::hasColumn('payment_methods', 'color_secondary')) $table->dropColumn('color_secondary');
            if (Schema::hasColumn('payment_methods', 'color_primary')) $table->dropColumn('color_primary');
            if (Schema::hasColumn('payment_methods', 'type')) $table->dropColumn('type');
        });
    }
};
