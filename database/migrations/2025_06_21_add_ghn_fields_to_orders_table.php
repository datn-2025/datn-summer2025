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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ghn_order_code')->nullable()->after('order_code');
            $table->integer('ghn_service_id')->nullable()->after('shipping_fee');
            $table->timestamp('expected_delivery_time')->nullable()->after('ghn_service_id');
            $table->json('ghn_shipping_info')->nullable()->after('expected_delivery_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ghn_order_code', 'ghn_service_id', 'expected_delivery_time', 'ghn_shipping_info']);
        });
    }
};
