<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('account_number')->unique();

            $table->string('name');

            $table->enum('type', [
                'cash',
                'bank',
                'mobile_money',
                'equity',
                'revenue',
                'expense',
                'asset',
                'liability',
            ])->default('bank');

            $table->string('bank_name')->nullable();

            $table->string('account_identifier')->nullable();

            $table->decimal('current_balance', 15, 2)->default(0);

            $table->boolean('is_active')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('is_active');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_accounts');
    }
};
