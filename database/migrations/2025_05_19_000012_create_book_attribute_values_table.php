<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_attribute_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('book_id');
            $table->uuid('attribute_value_id');
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');

            $table->foreign('attribute_value_id')
                ->references('id')
                ->on('attribute_values')
                ->onDelete('cascade');

            // Add indexes for relationships and common queries
            $table->index('book_id');
            $table->index('attribute_value_id');
            $table->unique(['book_id', 'attribute_value_id']); // Prevent duplicate attribute values for a book
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_attribute_values');
    }
};
