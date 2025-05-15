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
        Schema::create('order_items', function (Blueprint $table) {
           $table->uuid('id')->primary(); // id int [pk, increment]

            $table->uuid('order_id');
            $table->uuid('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // đơn giá tại thời điểm đặt hàng

            $table->timestamps();

            // Index
            $table->index('order_id');
            $table->index('product_id');

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
