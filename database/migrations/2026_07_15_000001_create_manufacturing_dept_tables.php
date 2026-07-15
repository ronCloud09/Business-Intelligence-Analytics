<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturing_work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('source_id')->unique(); // their original varchar id, e.g. "WO-1023"
            $table->string('name')->nullable();
            $table->string('specs')->nullable();
            $table->string('status')->nullable(); // Finished, Pending, Building, QC Check, Cancelled
            $table->string('due')->nullable();
            $table->string('source')->nullable();
            $table->string('assigned')->nullable();
            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps(); // when WE synced it
        });

        Schema::create('manufacturing_qc_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique(); // their original int id
            $table->unsignedBigInteger('source_session_id')->nullable();
            $table->string('check_id')->nullable();
            $table->decimal('value', 12, 2)->nullable();
            $table->string('verdict')->nullable(); // Pass, Warn, '' (ungraded)
            $table->text('note')->nullable();
            $table->timestamp('source_created_at')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturing_qc_results');
        Schema::dropIfExists('manufacturing_work_orders');
    }
};
