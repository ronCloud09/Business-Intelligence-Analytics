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
        Schema::create('procurement_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->string('supplier');
            $table->string('item_description');
            $table->unsignedInteger('quantity');
            $table->decimal('total_cost', 14, 2);
            $table->enum('status', ['draft', 'submitted', 'approved', 'in_transit', 'received', 'cancelled'])->default('submitted');
            $table->date('expected_date')->nullable();
            $table->boolean('expedited')->default(false);
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_orders');
    }
};
