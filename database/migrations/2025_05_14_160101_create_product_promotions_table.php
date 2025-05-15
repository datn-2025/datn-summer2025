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
        Schema::create('product_promotions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]

            $table->uuid('product_id');
            $table->uuid('promotion_id');

            $table->timestamps();

            // Unique constraint: mỗi sản phẩm chỉ được gán 1 lần cho 1 khuyến mãi
            $table->unique(['product_id', 'promotion_id']);

            // Indexes
            $table->index('product_id');
            $table->index('promotion_id');

            // Foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_promotions');
    }
};
