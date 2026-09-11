<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->string('loan_number')->unique();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            $table->foreignId('loan_product_id')
                ->constrained('loan_products')
                ->restrictOnDelete();

            /*
             * Loan amounts
             */
            $table->decimal('principal_amount', 15, 2);

            $table->decimal('interest_rate', 8, 4);

            $table->enum('interest_type', [
                'flat',
                'reducing_balance'
            ]);

            $table->decimal('interest_amount', 15, 2)->default(0);

            $table->decimal('fees_amount', 15, 2)->default(0);

            $table->decimal('penalty_amount', 15, 2)->default(0);

            $table->decimal('total_payable', 15, 2)->default(0);

            $table->decimal('amount_paid', 15, 2)->default(0);

            $table->decimal('outstanding_balance', 15, 2)->default(0);

            /*
             * Repayment settings
             */
            $table->unsignedInteger('duration');

            $table->enum('repayment_frequency', [
                'daily',
                'weekly',
                'biweekly',
                'monthly'
            ]);

            /*
             * Important dates
             */
            $table->date('loan_date');

            $table->date('disbursement_date')->nullable();

            $table->date('first_payment_date')->nullable();

            $table->date('maturity_date')->nullable();

            /*
             * Loan lifecycle
             */
            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'disbursed',
                'active',
                'partially_paid',
                'fully_paid',
                'overdue',
                'defaulted',
                'written_off',
                'cancelled'
            ])->default('draft');

            /*
             * Administration
             */
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('disbursed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('status');
            $table->index('loan_date');
            $table->index('maturity_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};