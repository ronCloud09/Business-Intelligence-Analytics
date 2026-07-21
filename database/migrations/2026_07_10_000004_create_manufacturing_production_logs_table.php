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
        Schema::create('manufacturing_production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturing_machine_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->unsignedInteger('units_produced')->default(0);
            $table->unsignedInteger('units_target')->default(0);
            $table->unsignedInteger('defect_count')->default(0);
            $table->timestamps();

            $table->index('log_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_production_logs');
    }
};
