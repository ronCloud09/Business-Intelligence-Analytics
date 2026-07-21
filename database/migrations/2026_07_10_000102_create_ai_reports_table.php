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
        Schema::create('ai_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_generation_id')->constrained()->cascadeOnDelete();
            $table->enum('type', [
                'executive_summary',
                'top_recommendations',
                'risk_analysis',
                'business_health',
                'department_insights',
            ]);
            // A single department name when type = department_insights /
            // an event-driven single-department insight; null otherwise.
            $table->string('department')->nullable();
            $table->json('content');
            $table->timestamps();

            $table->index(['type', 'department']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_reports');
    }
};
