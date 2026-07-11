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
        Schema::create('compliance_risks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('standard')->nullable();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_review', 'mitigated', 'closed'])->default('open');
            $table->text('description')->nullable();
            $table->date('identified_date');
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index(['severity', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_risks');
    }
};
