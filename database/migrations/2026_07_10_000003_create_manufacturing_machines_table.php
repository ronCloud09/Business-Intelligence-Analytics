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
        Schema::create('manufacturing_machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('line')->nullable();
            $table->enum('status', ['running', 'idle', 'down', 'maintenance'])->default('running');
            $table->unsignedInteger('downtime_minutes_today')->default(0);
            $table->decimal('production_rate', 6, 2)->default(0);
            $table->timestamp('last_status_change_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_machines');
    }
};
