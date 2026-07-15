<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ecommerce_dept_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('source_id')->unique();

            $table->string('name');
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('processor')->nullable();
            $table->text('specs')->nullable();

            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('rating', 4, 2)->nullable();

            $table->string('image_url')->nullable();
            $table->string('badge')->nullable();
            $table->string('tag')->nullable();

            $table->string('os')->nullable();
            $table->string('cpu')->nullable();
            $table->string('gpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('motherboard')->nullable();
            $table->string('psu')->nullable();
            $table->string('case')->nullable();
            $table->string('cooler')->nullable();

            $table->boolean('is_sold_out')->default(false);
            $table->integer('forge_points')->default(0);
            $table->string('shipping_status')->nullable();
            $table->string('promo_tag')->nullable();

            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();

            $table->timestamps();
        });

        Schema::create('ecommerce_dept_prebuilt_configs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('source_id')->unique();

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);

            $table->string('image_url')->nullable();

            $table->unsignedBigInteger('cpu_id')->nullable();
            $table->unsignedBigInteger('gpu_id')->nullable();
            $table->unsignedBigInteger('motherboard_id')->nullable();
            $table->unsignedBigInteger('ram_id')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable();
            $table->unsignedBigInteger('power_supply_id')->nullable();
            $table->unsignedBigInteger('pc_case_id')->nullable();
            $table->unsignedBigInteger('cooler_id')->nullable();

            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();

            $table->timestamps();
        });

        Schema::create('ecommerce_dept_configurator_configs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('source_id')->unique();

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);

            $table->string('image_url')->nullable();
            $table->string('platform')->nullable();
            $table->string('tier')->nullable();

            $table->unsignedBigInteger('cpu_id')->nullable();
            $table->unsignedBigInteger('gpu_id')->nullable();
            $table->unsignedBigInteger('motherboard_id')->nullable();
            $table->unsignedBigInteger('ram_id')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable();
            $table->unsignedBigInteger('power_supply_id')->nullable();
            $table->unsignedBigInteger('pc_case_id')->nullable();
            $table->unsignedBigInteger('cooler_id')->nullable();

            $table->decimal('rating', 4, 2)->nullable();
            $table->integer('review_count')->default(0);

            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecommerce_dept_configurator_configs');
        Schema::dropIfExists('ecommerce_dept_prebuilt_configs');
        Schema::dropIfExists('ecommerce_dept_products');
    }
};