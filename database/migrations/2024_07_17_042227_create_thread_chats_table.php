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
        Schema::create('thread_chats', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('chat');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('thread_id')->constrained();
            $table->enum('by', array('awardee', 'admin'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_chats');
    }
};
