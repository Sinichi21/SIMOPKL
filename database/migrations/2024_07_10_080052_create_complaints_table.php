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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('complaint_id');
            $table->text('content');
            $table->enum('status', array('open', 'close', 'progress'))->default('open');
            $table->foreignId('awardee_id')->constrained();
            $table->string('fullname');
            $table->string('bpi_number');
            $table->string('faculty');
            $table->string('study_program');
            $table->string('email');
            $table->foreignId('complaint_type_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
