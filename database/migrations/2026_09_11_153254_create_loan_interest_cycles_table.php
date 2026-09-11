<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_interest_cycles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')
                ->constrained('loans')
                ->cascadeOnDelete();

            $table->unsignedInteger('cycle_number');

            $table->date('cycle_date');

            $table->decimal('opening_balance', 15, 2);

            $table->decimal('interest_rate', 8, 4);

            $table->decimal('interest_amount', 15, 2);

            $table->decimal('payment_amount', 15, 2)
                ->default(0);

            $table->decimal('closing_balance', 15, 2);

            $table->enum('status', [
                'pending',
                'applied',
                'skipped'
            ])->default('pending');

            $table->timestamps();

            $table->unique([
                'loan_id',
                'cycle_number'
            ]);

            $table->index([
                'loan_id',
                'cycle_date'
            ]);

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_interest_cycles');
    }
};