<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('service_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('vehicle_model');
            $table->string('vehicle_number');

            $table->date('booking_date');
            $table->time('booking_time');

            $table->text('notes')->nullable();

            // ✅ STATUS DIPERBAIKI
            $table->enum('status', [
                'pending',
                'confirmed',
                'ongoing',
                'completed',
                'cancelled'
            ])->default('pending');

            // ✅ PAYMENT STATUS
            $table->enum('payment_status', [
                'unpaid',
                'paid'
            ])->default('unpaid');

            // ✅ PAYMENT METHOD SESUAI CONTROLLER
            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'e_wallet'
            ])->nullable();

            $table->decimal('total_price', 10, 2);

            // (opsional) point tersimpan di booking
            $table->integer('points_given')->default(0);

            $table->string('receipt_number')->nullable()->unique();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_bookings');
    }
};
