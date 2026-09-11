<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();

            $table->text('description')->nullable();

            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);

            $table->decimal('interest_rate', 8, 4);

            $table->enum('interest_type', [
                'flat',
                'reducing_balance'
            ])->default('flat');

            $table->enum('repayment_frequency', [
                'daily',
                'weekly',
                'biweekly',
                'monthly'
            ])->default('monthly');

            $table->unsignedInteger('min_duration');
            $table->unsignedInteger('max_duration');

            $table->boolean('is_active')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};