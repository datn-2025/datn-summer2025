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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID cho bảng categories
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->uuid('parent_id')->nullable(); // hỗ trợ danh mục con
            $table->timestamps();

            // Index
            $table->index('parent_id');
            // Foreign key: tự liên kết
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
