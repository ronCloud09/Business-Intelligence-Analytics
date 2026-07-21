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
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('generation_number');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('triggered_by', ['scheduler', 'manual', 'event'])->default('scheduler');
            $table->string('trigger_reason')->nullable(); // e.g. "inventory_low_stock", "manual_refresh"
            $table->string('provider')->nullable(); // e.g. "gemini"
            $table->string('model')->nullable(); // e.g. "gemini-2.5-flash"
            $table->unsignedInteger('input_tokens')->default(0);
            $table->unsignedInteger('output_tokens')->default(0);
            $table->boolean('is_current')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('is_current');
            $table->index('generation_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_generations');
    }
};
