<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_dept_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('inventory_dept_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->string('name');
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('barangay')->nullable();
            $table->text('address_description')->nullable();
            $table->string('country')->nullable();
            $table->integer('capacity_units')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_dept_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->string('sku')->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('source_category_id')->nullable();
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_dept_stock_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->unsignedBigInteger('source_item_id');
            $table->unsignedBigInteger('source_warehouse_id');
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('reorder_threshold')->default(0);
            $table->timestamps();

            $table->index('source_item_id');
            $table->index('source_warehouse_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_dept_stock_levels');
        Schema::dropIfExists('inventory_dept_items');
        Schema::dropIfExists('inventory_dept_warehouses');
        Schema::dropIfExists('inventory_dept_categories');
    }
};
