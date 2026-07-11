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
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_generation_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('level', ['info', 'warning', 'error'])->default('info');
            $table->string('message');
            $table->json('context')->nullable();
            $table->timestamps();

            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
