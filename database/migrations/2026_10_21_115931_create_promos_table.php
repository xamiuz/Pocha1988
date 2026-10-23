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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tagline')->nullable();
            $table->string('badge_text')->nullable()->default('PROMO SPESIAL');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('promo_code')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('promo_price', 12, 2)->nullable();
            $table->foreignId('menu_item_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->string('button_text')->default('Pesan Sekarang');
            $table->string('button_link')->nullable()->default('#menu');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
