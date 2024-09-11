<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMonthlyRequestsToChatRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('chat_requests', function (Blueprint $table) {
            $table->json('monthly_requests')->nullable();
        });
    }

    public function down()
    {
        Schema::table('chat_requests', function (Blueprint $table) {
            $table->dropColumn('monthly_requests');
        });
    }
}

