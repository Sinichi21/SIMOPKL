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
        Schema::create('menu_icons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('url');
            $table->enum('menu', array('FaQ', 'Pengaduan', 'Awardee', 'Gallery'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_icons');
    }
};
