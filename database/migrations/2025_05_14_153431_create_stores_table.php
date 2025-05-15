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
        Schema::create('stores', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID cho bảng stores
            $table->uuid('user_id'); // liên kết đến bảng users
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->string('website', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('logo_url', 255)->nullable();
            $table->string('cover_image_url', 255)->nullable();
            $table->enum('status', ['Hoạt Động', 'Không Hoạt Động', 'Chờ Duyệt'])->default('Chờ Duyệt');
            $table->text('address')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('status');

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
