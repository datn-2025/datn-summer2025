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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID cho bảng orders
            $table->uuid('user_id');
            $table->uuid('order_status_id');
            $table->uuid('payment_status_id');
            $table->uuid('payment_method_id');

            $table->string('coupon_code', 50)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->text('shipping_address');

            $table->dateTime('order_date');
            $table->dateTime('shipped_date')->nullable();

            $table->timestamps(); // created_at & updated_at

            // Indexes
            $table->index('user_id');
            $table->index('order_status_id');
            $table->index('payment_status_id');
            $table->index('payment_method_id');
            $table->index('order_date');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_status_id')->references('id')->on('order_statuses')->onDelete('restrict');
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses')->onDelete('restrict');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
