<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 100)->unique();
            $table->text('description')->nullable();
            $table->decimal('discount_percent', 8, 2)->nullable();
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->decimal('min_order_value', 12, 2)->nullable();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->integer('quantity');
            $table->string('status', 50)->default('active');
            $table->softDeletes();
            $table->timestamps();

            // Add indexes for frequently accessed columns
            $table->index('code');
            $table->index('status');
            $table->index('valid_from');
            $table->index('valid_to');
            $table->index(['status', 'valid_from', 'valid_to']); // Composite index for active voucher searches
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
