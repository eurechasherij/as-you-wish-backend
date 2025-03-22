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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->dateTime('event_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('slug');
            $table->index('code');
            $table->index('event_name');
            $table->index('event_date');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
