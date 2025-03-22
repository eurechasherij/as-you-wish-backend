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
            $table->id();
            $table->foreignId('event_id')->constrained()->onUpdate('cascade')->delete('cascade');
            $table->text('content');
            $table->string('status')->default('pending');
            $table->boolean('displayed')->default(false);
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type');
            $table->timestamps();

            $table->index('event_id');
            $table->index(['sender_id', 'sender_type']);
            $table->index('status');
            $table->index('displayed');
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
