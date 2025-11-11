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
        Schema::create('awardees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nim');
            $table->string('fullname');
            $table->string('username');
            $table->enum('degree', array('S1', 'S2', 'S3'));
            $table->string('phone_number');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('study_program_id')->constrained();
            $table->year('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awardees');
    }
};
