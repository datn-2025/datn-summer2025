<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('book_id');
            $table->string('image_url', 500);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');

            // Add index for book_id for faster lookups
            $table->index('book_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_images');
    }
};
