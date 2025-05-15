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
        Schema::create('revenue_reports', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]

            $table->uuid('store_id');
            $table->date('report_date'); // ngày thống kê
            $table->integer('total_orders')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0.00);

            $table->timestamp('created_at')->useCurrent();
            // không cần updated_at vì dữ liệu không thường xuyên cập nhật

            // Indexes
            $table->index(['store_id', 'report_date'], 'idx_store_date');
            $table->index('report_date');

            // Foreign key
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_reports');
    }
};
