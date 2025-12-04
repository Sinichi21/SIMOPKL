<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->unsignedBigInteger('document_type_id');
            $table->string('file_path');
            $table->timestamps();

            // Relasi
            $table->foreign('registration_id')
                ->references('id')->on('registers')
                ->onDelete('cascade');

            $table->foreign('document_type_id')
                ->references('id')->on('document_types')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_documents');
    }
};
