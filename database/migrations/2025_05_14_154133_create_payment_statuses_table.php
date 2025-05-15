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
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]
            $table->enum('name', ['Đang Chờ Xử Lý', 'Chưa Thanh Toán', 'Thanh Toán Thành Công', 'Thanh toán thất bại'])->unique();
            $table->text('description')->nullable();
            $table->timestamps();

            // Index
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_statuses');
    }
};
