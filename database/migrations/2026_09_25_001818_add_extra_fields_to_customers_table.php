<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('email');
            $table->string('gender', 20)->nullable()->after('date_of_birth');
            $table->string('id_type', 50)->nullable()->after('gender');
            $table->string('id_number', 100)->nullable()->after('id_type');

            $table->string('city', 100)->nullable()->after('address');
            $table->string('region', 100)->nullable()->after('city');
            $table->string('digital_address', 50)->nullable()->after('region');

            $table->string('employment_address')->nullable()->after('employer');

            $table->string('emergency_contact_name', 150)->nullable()->after('monthly_income');
            $table->string('emergency_contact_relationship', 100)->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_phone', 50)->nullable()->after('emergency_contact_relationship');
            $table->text('emergency_contact_address')->nullable()->after('emergency_contact_phone');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'id_type',
                'id_number',
                'city',
                'region',
                'digital_address',
                'employment_address',
                'emergency_contact_name',
                'emergency_contact_relationship',
                'emergency_contact_phone',
                'emergency_contact_address',
            ]);
        });
    }
};
