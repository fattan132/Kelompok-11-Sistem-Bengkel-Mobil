<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_bookings', function (Blueprint $table) {
            $table->decimal('service_fee', 10, 2)->default(0)->after('total_price');
            $table->decimal('subtotal', 10, 2)->nullable()->after('service_fee');
            $table->decimal('tax_amount', 10, 2)->nullable()->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_bookings', function (Blueprint $table) {
            $table->dropColumn('service_fee');
            $table->dropColumn('subtotal');
            $table->dropColumn('tax_amount');
        });
    }
};
