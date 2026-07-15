<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_dept_invoices', function (Blueprint $table) {
            $table->id();

            // Original Finance DB ID
            $table->integer('source_id')->unique();

            $table->integer('client_id')->nullable();

            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();

            $table->decimal('invoice_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('outstanding_amount', 12, 2)->default(0);

            $table->string('status')->nullable();

            // Original timestamps
            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();

            // Local sync timestamps
            $table->timestamps();

            $table->index('status');
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_dept_invoices');
    }
};