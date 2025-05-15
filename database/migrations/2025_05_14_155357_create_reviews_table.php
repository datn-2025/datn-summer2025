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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]

            $table->uuid('user_id');
            $table->uuid('product_id');
            $table->integer('rating')->unsigned(); // nên nằm trong khoảng 1–5
            $table->text('comment')->nullable();

            $table->timestamps();

            // Index
            $table->unique(['user_id', 'product_id']); // mỗi user chỉ đánh giá 1 lần / sản phẩm
            $table->index('rating');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
