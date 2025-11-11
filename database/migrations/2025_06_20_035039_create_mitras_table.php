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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('partner_name');
            $table->string('descriuption')->nullable();
            $table->string('enail')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number');
            $table->text('address')->nullable();
            $table->string('website_address')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
