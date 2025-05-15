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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]

            $table->uuid('order_id');
            $table->uuid('payment_method_id');
            $table->uuid('payment_status_id');

            $table->decimal('amount', 10, 2);
            $table->string('transaction_id', 255)->nullable(); // mã giao dịch từ gateway
            $table->string('payment_gateway', 50)->nullable(); // ví dụ: vnpay, momo, stripe

            $table->dateTime('payment_date')->nullable();
            $table->timestamps();

            // Index
            $table->index('order_id');
            $table->index('payment_status_id');
            $table->index('payment_method_id');
            $table->index('transaction_id');

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('restrict');
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
