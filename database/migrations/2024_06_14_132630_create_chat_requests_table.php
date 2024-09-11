<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('chat_requests', function (Blueprint $table) {
            $table->id();
            $table->text('context');
            $table->integer('total_request')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_requests');
    }
}

?>
