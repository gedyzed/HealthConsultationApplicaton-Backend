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
        Schema::create('doctors', function (Blueprint $table) {            
            $table->foreignId("doctor_id")->primary()->constrained("users", "user_id")->onDelete("cascade");
            $table->string("fullName");
            $table->text("aboutMe");
            $table->unsignedInteger("yearOfExperience");
            $table->decimal("pricing", 10, 2);
            $table->string("image");
            $table->string("idImage");

            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
