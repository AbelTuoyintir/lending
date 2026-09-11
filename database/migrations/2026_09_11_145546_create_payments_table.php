<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('payment_number')->unique();

            $table->foreignId('loan_id')
                ->constrained('loans')
                ->restrictOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            $table->decimal('amount', 15, 2);

            $table->enum('payment_method', [
                'cash',
                'mobile_money',
                'bank_transfer',
                'card',
                'other'
            ]);

            /*
             * Internal payment reference.
             * This must be unique to prevent duplicate payments.
             */
            $table->string('reference')->unique();

            $table->dateTime('payment_date');

            $table->enum('status', [
                'completed',
                'reversed'
            ])->default('completed');

            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('loan_id');
            $table->index('customer_id');
            $table->index('payment_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};