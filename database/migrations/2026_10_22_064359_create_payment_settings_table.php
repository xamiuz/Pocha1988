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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();

            // 1. QRIS Toko
            $table->boolean('is_qris_active')->default(true);
            $table->string('qris_image')->nullable();
            $table->string('qris_merchant_name')->default('1988 POCHA SAMARINDA');
            $table->text('qris_notes')->nullable();

            // 2. Transfer Bank
            $table->boolean('is_transfer_active')->default(true);
            $table->string('bank_name')->default('Bank BCA');
            $table->string('bank_account_number')->default('869-1988-1988');
            $table->string('bank_account_name')->default('1988 Pocha Samarinda');

            $table->string('bank_2_name')->nullable()->default('Bank Mandiri');
            $table->string('bank_2_account_number')->nullable()->default('148-00-1988-1988');
            $table->string('bank_2_account_name')->nullable()->default('1988 Pocha Samarinda');

            // 3. COD (Bayar di Tempat)
            $table->boolean('is_cod_active')->default(true);
            $table->text('cod_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
