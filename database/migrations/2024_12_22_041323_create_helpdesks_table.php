<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
          * Run the migrations.
          */
    public function up()
    {
        Schema::create('helpdesk', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('ticket_id');
            $table->enum('status', ['new', 'waiting', 'closed']);
            $table->text('conversation')->nullable();
            $table->timestamps();
        });
    }

    /**
          * Reverse the migrations.
          */
    public function down()
    {
        Schema::dropIfExists('helpdesk');
    }
};