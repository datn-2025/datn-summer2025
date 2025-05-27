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
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->uuid('book_id');
            $table->uuid('book_format_id');

            $table->integer('quantity')->default(1);
            $table->json('attribute_value_ids')->nullable();

            $table->decimal('price', 12, 2);

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('book_format_id')->references('id')->on('book_formats')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('book_id');
            $table->index('book_format_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
