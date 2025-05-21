<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('restrict');
            $table->string('transaction_id', 100)->nullable();
            $table->decimal('amount', 12, 2);
            $table->timestamp('paid_at')->nullable();
            $table->uuid('payment_status_id');
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses');
            $table->timestamps();

            // Foreign key constraint for order
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('restrict');

            // Add indexes for frequently accessed columns
            $table->index('order_id');
            $table->index('payment_method_id');
            $table->index('transaction_id');
            $table->index('paid_at');

            // Unique constraint for transaction_id if not null
            $table->unique('transaction_id', 'payments_transaction_id_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
