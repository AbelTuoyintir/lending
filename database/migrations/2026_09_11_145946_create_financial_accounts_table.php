<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('transaction_number')->unique();

            $table->foreignId('financial_account_id')
                ->constrained('financial_accounts')
                ->restrictOnDelete();

            $table->foreignId('loan_id')
                ->nullable()
                ->constrained('loans')
                ->nullOnDelete();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->nullOnDelete();

            $table->enum('type', [
                'loan_disbursement',
                'principal_repayment',
                'interest_payment',
                'fee_payment',
                'penalty_payment',
                'refund',
                'deposit',
                'withdrawal',
                'adjustment'
            ]);

            $table->decimal('debit', 15, 2)->default(0);

            $table->decimal('credit', 15, 2)->default(0);

            $table->decimal('balance_after', 15, 2);

            $table->string('reference')->nullable();

            $table->text('description')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('transaction_date');

            $table->timestamps();

            $table->index('financial_account_id');
            $table->index('loan_id');
            $table->index('customer_id');
            $table->index('transaction_date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};