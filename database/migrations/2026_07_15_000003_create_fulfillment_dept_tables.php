<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fulfillment_orders', function (Blueprint $table) {
            $table->id();
            $table->string('source_id')->unique();
            $table->string('customer_name')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('qty')->nullable();
            $table->string('status')->nullable();
            $table->date('due_date')->nullable();
            $table->string('address')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fulfillment_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->string('shipment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('qty')->nullable();
            $table->string('courier')->nullable();
            $table->string('box_used')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('status')->nullable();
            $table->text('address')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fulfillment_delivery_men', function (Blueprint $table) {
            $table->id();
            $table->string('source_id')->unique();
            $table->integer('age')->nullable();
            $table->string('license_num')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('shipping_provider_id')->nullable();
            $table->timestamps();
        });

        Schema::create('fulfillment_packing_errors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->string('order_id')->nullable();
            $table->string('material')->nullable();
            $table->string('reason')->nullable();
            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fulfillment_packing_materials', function (Blueprint $table) {
            $table->id();
            $table->string('source_id')->unique();
            $table->string('name')->nullable();
            $table->integer('stock_qty')->default(0);
            $table->integer('low_stock_threshold')->default(0);
            $table->boolean('is_box')->default(false);
            $table->string('box_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fulfillment_packing_materials');
        Schema::dropIfExists('fulfillment_packing_errors');
        Schema::dropIfExists('fulfillment_delivery_men');
        Schema::dropIfExists('fulfillment_shipments');
        Schema::dropIfExists('fulfillment_orders');
    }
};
