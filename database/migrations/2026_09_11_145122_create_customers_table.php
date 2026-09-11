<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('customer_number')->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();

            $table->decimal('monthly_income', 15, 2)->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'blacklisted'
            ])->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('phone');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};