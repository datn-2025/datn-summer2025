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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID cho bảng products
            $table->uuid('store_id');;
            $table->uuid('category_id')->nullable();

            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('quantity_in_stock')->default(0);
            $table->string('sku', 50)->unique();

            $table->enum('status', ['Còn Hàng', 'Hết Hàng Tồn Kho', 'Ngừng Bán'])->default('Còn Hàng');
            $table->string('image', 255)->nullable();

            $table->timestamps();

            // Indexes
            $table->index('store_id');
            $table->index('category_id');
            $table->index('status');

            // Foreign keys
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
