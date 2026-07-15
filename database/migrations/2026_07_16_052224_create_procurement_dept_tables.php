<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('procurement_dept_companies', function (Blueprint $table) {
            $table->id();

            // Original Procurement DB ID
            $table->unsignedBigInteger('source_id')->unique();

            $table->string('company_name');
            $table->string('industry')->nullable();
            $table->string('company_email')->nullable();
            $table->string('phone_no')->nullable();

            $table->string('admin_name')->nullable();
            $table->unsignedBigInteger('admin_user_id')->nullable();

            $table->string('employee_table_name')->nullable();

            $table->string('status')->nullable();

            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();

            $table->timestamps();

            $table->index('company_name');
            $table->index('status');
        });

        Schema::create('procurement_dept_requisitions', function (Blueprint $table) {
            $table->id();

            // Original Procurement DB ID
            $table->unsignedBigInteger('source_id')->unique();

            $table->string('req_number')->nullable();

            $table->string('item');
            $table->integer('qty')->default(0);
            $table->string('uom')->nullable();

            $table->string('delivery_status')->nullable();
            $table->string('department')->nullable();
            $table->string('requested_by')->nullable();

            $table->string('status')->nullable();

            $table->date('date_requested')->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();

            $table->timestamps();

            $table->index('req_number');
            $table->index('department');
            $table->index('status');
            $table->index('delivery_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_dept_requisitions');
        Schema::dropIfExists('procurement_dept_companies');
    }
};