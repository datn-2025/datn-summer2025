<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applied_vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('voucher_id');
            $table->uuid('order_id')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->integer('usage_count')->default(1);
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('voucher_id')
                ->references('id')
                ->on('vouchers')
                ->onDelete('restrict');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('restrict');

            // Add indexes for frequently accessed columns
            $table->index('user_id');
            $table->index('voucher_id');
            $table->index('order_id');
            $table->index('used_at');
            $table->unique(['user_id', 'voucher_id', 'order_id']); // Prevent duplicate voucher applications
        });
    }

    public function down()
    {
        Schema::dropIfExists('applied_vouchers');
    }
};
