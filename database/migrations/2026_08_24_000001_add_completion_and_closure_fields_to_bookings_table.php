<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'final_price')) {
                $table->decimal('final_price', 12, 2)->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('bookings', 'commission')) {
                $table->decimal('commission', 12, 2)->nullable()->after('interest_rate');
            }
        });

        // Change status column to string to accommodate new statuses seamlessly
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status', 50)->default('new')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'final_price')) {
                $table->dropColumn('final_price');
            }
            if (Schema::hasColumn('bookings', 'commission')) {
                $table->dropColumn('commission');
            }
        });
    }
};
