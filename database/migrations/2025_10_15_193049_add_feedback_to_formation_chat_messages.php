<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('formation_chat_messages', function (Blueprint $table) {
            // -1 = bad, 0 = none, 1 = good
            $table->tinyInteger('feedback')->default(0)->comment('1=like, -1=dislike, 0=none')->after('role');
            $table->text('feedback_reason')->nullable()->after('feedback');
        });
    }

    public function down(): void
    {
        Schema::table('formation_chat_messages', function (Blueprint $table) {
            $table->dropColumn(['feedback', 'feedback_reason']);
        });
    }
};
