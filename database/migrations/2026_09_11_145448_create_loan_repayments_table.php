<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')
                ->constrained('loans')
                ->cascadeOnDelete();

            $table->unsignedInteger('installment_number');

            $table->date('due_date');

            $table->decimal('principal_amount', 15, 2)->default(0);

            $table->decimal('interest_amount', 15, 2)->default(0);

            $table->decimal('fee_amount', 15, 2)->default(0);

            $table->decimal('penalty_amount', 15, 2)->default(0);

            $table->decimal('total_amount', 15, 2);

            $table->decimal('amount_paid', 15, 2)->default(0);

            $table->decimal('outstanding_amount', 15, 2);

            $table->enum('status', [
                'pending',
                'partial',
                'paid',
                'overdue',
                'waived',
            ])->default('pending');

            $table->date('paid_date')->nullable();

            $table->timestamps();

            $table->unique([
                'loan_id',
                'installment_number',
            ]);

            $table->index('due_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_repayments');
    }
};
