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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('product_name');
            $table->string('customer_segment')->nullable();
            $table->unsignedInteger('units_sold');
            $table->decimal('revenue', 14, 2);
            $table->date('order_date');
            $table->boolean('is_new_customer')->default(false);
            $table->timestamps();

            $table->index('order_date');
            $table->index('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
