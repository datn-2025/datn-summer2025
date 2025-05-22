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
        Schema::create('contacts', function (Blueprint $table) {
           $table->uuid('id'); // Khóa chính tự tăng
            $table->string('name'); // Tên người liên hệ
            $table->string('email')->unique(); // Email, cần unique
            $table->string('phone')->nullable(); // Số điện thoại, có thể null
            $table->text('address')->nullable(); // Địa chỉ, có thể null
            $table->text('note')->nullable(); // Ghi chú, có thể null
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
