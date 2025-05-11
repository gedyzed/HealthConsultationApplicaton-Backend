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
        Schema::create('educations', function (Blueprint $table) {
            $table->id("education_id")->onDelete("cascade");
            $table->foreignId("doctor_id")->constrained("doctors", "doctor_id");
            $table->string("degree");
            $table->string("fieldOfStudy");
            $table->string("institution");
            // $table->year("startYear");
            $table->year("endYear");
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educations');
    }
};
