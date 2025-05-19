<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->uuid('author_id');
            $table->uuid('brand_id');
            $table->uuid('category_id');
            $table->string('status', 50)->default('available');
            $table->string('cover_image', 500)->nullable();
            $table->string('isbn', 20)->nullable();
            $table->date('publication_date')->nullable();
            $table->integer('page_count')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('restrict');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');

            // Add indexes for frequently accessed columns and foreign keys
            $table->index('title');
            $table->index('slug');
            $table->index('status');
            $table->index('isbn');
            $table->index('author_id');
            $table->index('brand_id');
            $table->index('category_id');
            $table->index('publication_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
