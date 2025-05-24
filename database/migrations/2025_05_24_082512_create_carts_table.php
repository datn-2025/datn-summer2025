<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();                    // ID dạng UUID
            $table->uuid('user_id')->nullable();               // user_id nullable (guest có thể dùng)
            $table->uuid('book_id');                           // ID sản phẩm (sách)
            $table->uuid('book_format_id')->nullable();       // ID định dạng sách (nếu có)
            $table->integer('quantity')->default(1);          // Số lượng sản phẩm
            $table->decimal('price_at_addition', 12, 2);      // Giá khi thêm vào giỏ
            $table->timestamps();

            // Khóa ngoại (nếu có bảng users, books, book_formats)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('book_format_id')->references('id')->on('book_formats')->onDelete('cascade');

            // Đảm bảo mỗi user không có sản phẩm trùng (book + định dạng)
            $table->unique(['user_id', 'book_id', 'book_format_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
