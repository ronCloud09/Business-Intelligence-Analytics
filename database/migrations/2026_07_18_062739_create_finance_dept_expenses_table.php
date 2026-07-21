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
        Schema::create('finance_dept_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->string('month')->nullable();
            $table->decimal('total_expenses', 14, 2)->default(0);
            $table->decimal('percent_change', 5, 2)->default(0);
            $table->decimal('budget_used', 14, 2)->default(0);
            $table->decimal('budget_total', 14, 2)->default(0);
            $table->decimal('manufacturing', 14, 2)->default(0);
            $table->decimal('procurement', 14, 2)->default(0);
            $table->decimal('inventory', 14, 2)->default(0);
            $table->decimal('order_fulfillment', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_dept_expenses');
    }
};
