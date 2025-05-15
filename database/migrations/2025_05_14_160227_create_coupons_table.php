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
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]

            $table->string('code', 50)->unique(); // mã giảm giá duy nhất
            $table->text('description')->nullable();

            $table->decimal('discount_percent', 5, 2); // ví dụ: 15.00 = 15%
            $table->integer('max_uses')->default(1); // số lượt sử dụng tối đa
            $table->dateTime('expires_at')->nullable(); // ngày hết hạn

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Index
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
