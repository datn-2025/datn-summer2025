<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('address_id');
            $table->uuid('voucher_id')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->uuid('order_status_id');
            $table->foreign('order_status_id')->references('id')->on('order_statuses');
            $table->uuid('payment_status_id');
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses');
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('restrict');

            $table->foreign('voucher_id')
                ->references('id')
                ->on('vouchers')
                ->onDelete('restrict');

            // Add indexes for frequently accessed columns
            $table->index('user_id');
            $table->index('address_id');
            $table->index('voucher_id');
            $table->index('order_status_id');
            $table->index('payment_status_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']); // For user order history queries
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
