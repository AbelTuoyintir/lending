<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->foreignId('loan_repayment_id')
                ->nullable()
                ->constrained('loan_repayments')
                ->nullOnDelete();

            $table->decimal('principal_amount', 15, 2)->default(0);

            $table->decimal('interest_amount', 15, 2)->default(0);

            $table->decimal('fee_amount', 15, 2)->default(0);

            $table->decimal('penalty_amount', 15, 2)->default(0);

            $table->decimal('total_amount', 15, 2);

            $table->timestamps();

            $table->index('payment_id');
            $table->index('loan_repayment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};