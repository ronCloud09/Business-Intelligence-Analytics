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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['revenue', 'expense']);
            $table->string('category');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('PHP');
            $table->enum('status', ['paid', 'pending', 'overdue'])->default('paid');
            $table->date('transaction_date');
            $table->date('due_date')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'transaction_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
