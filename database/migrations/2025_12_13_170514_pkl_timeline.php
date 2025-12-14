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
        Schema::create('pkl_timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['upload', 'monev', 'ujian', 'visitasi', 'umum'])->default('umum');
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkl_timelines');
    }
};
