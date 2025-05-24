<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();

            $table->uuid('book_id');

            $table->uuid('book_format_id')->nullable();

            // Lưu attributes dưới dạng JSON (dễ mở rộng, lưu nhiều thuộc tính)
            $table->json('attributes')->nullable();

            $table->integer('quantity')->default(1);

            $table->decimal('price_at_addition', 12, 2);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            $table->foreign('book_format_id')->references('id')->on('book_formats')->onDelete('cascade');

            $table->unique(['user_id', 'book_id', 'book_format_id'], 'unique_cart_item');

            // Chỉ cần lưu trùng đúng cả 4 trường mới coi là cùng sản phẩm
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
