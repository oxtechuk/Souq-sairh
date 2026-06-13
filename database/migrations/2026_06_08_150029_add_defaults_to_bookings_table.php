<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('down_payment')->default(0)->change();
            $table->unsignedTinyInteger('duration_years')->default(0)->change();
            $table->unsignedBigInteger('monthly_installment')->default(0)->change();
            $table->unsignedBigInteger('total_price')->default(0)->change();
        });
    }

    public function down(): void
    {
    }
};