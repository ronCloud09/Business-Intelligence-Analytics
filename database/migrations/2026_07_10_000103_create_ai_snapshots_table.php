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
        Schema::create('ai_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_generation_id')->constrained()->cascadeOnDelete();
            // The raw aggregated KPI payload that was sent to the AI provider
            // for this generation, kept for audit/reproducibility — never
            // raw database rows, always the summarized aggregator output.
            $table->json('payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_snapshots');
    }
};
