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
        Schema::create('store_payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('store_id');
            $table->uuid('payment_method_id');

            $table->boolean('enabled')->default(true);

            // ⚙️ Cấu hình ngân hàng (Bank Transfer)
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();

            // ⚙️ Cấu hình VNPAY (sandbox hoặc real)
            $table->string('vnpay_merchant_code')->nullable();
            $table->string('vnpay_secret_key')->nullable();
            $table->string('vnpay_return_url')->nullable();

            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');

            // Mỗi store chỉ thiết lập 1 phương thức 1 lần
            $table->unique(['store_id', 'payment_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_payment_methods');
    }
};
