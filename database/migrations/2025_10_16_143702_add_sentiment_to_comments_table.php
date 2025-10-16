<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSentimentToCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->string('sentiment')->nullable()->after('content'); // positif, negatif, neutre
            $table->boolean('flagged')->default(false)->after('sentiment'); // pour modération
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['sentiment', 'flagged']);
        });
    }
}
