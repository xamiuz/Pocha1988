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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->foreignId('delivery_zone_id')->nullable()->constrained()->nullOnDelete();
            $table->string('delivery_district'); // Samarinda District
            $table->text('delivery_address');
            $table->text('delivery_notes')->nullable();

            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            $table->string('payment_method')->default('qris'); // qris, transfer_bca, transfer_mandiri, cod
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('payment_proof')->nullable();

            // Status alur: pending, cooking, on_delivery, delivered, cancelled
            $table->string('status')->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cooking_at')->nullable();
            $table->timestamp('delivering_at')->nullable();
            $table->timestamp('delivered_at')->nullable(); // Ditandai saat user menggeser konfirmasi atau driver selesai

            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
