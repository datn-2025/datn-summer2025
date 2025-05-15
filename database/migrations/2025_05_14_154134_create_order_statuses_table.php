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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]
            $table->enum('name', ['Chờ Xử Lý', 'Đang Giao', 'Đã Giao', 'Đã Hoàn Thành', 'Đã Hủy'])->unique();
            $table->text('description')->nullable();
            $table->timestamps(); // created_at & updated_at

            // Index
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
