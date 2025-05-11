<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {

            $table->id("message_id")->onDelete("cascade");
            $table->foreignId('receiver_id')->constrained('users',"user_id");
            $table->foreignId('sender_id')->constrained('users',"user_id");
            $table->date("date");
            $table->time("time");
            $table->text("message");
            $table->string("tokens");
            $table->timestamps();
            // $table->id();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
