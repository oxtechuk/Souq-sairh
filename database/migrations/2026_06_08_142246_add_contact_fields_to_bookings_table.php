<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('city')->nullable()->after('client_email');
            $table->string('salary_range')->nullable()->after('city');
            $table->string('obligations_range')->nullable()->after('salary_range');
            $table->string('contact_type')->nullable()->after('obligations_range');
            $table->string('tax_number')->nullable()->after('contact_type');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['city', 'salary_range', 'obligations_range', 'contact_type', 'tax_number']);
        });
    }
};
