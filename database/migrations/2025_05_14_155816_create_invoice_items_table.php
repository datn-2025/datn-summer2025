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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]

            $table->uuid('invoice_id');
            $table->uuid('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // đơn giá tại thời điểm xuất hóa đơn

            $table->timestamps();

            // Indexes
            $table->index('invoice_id');
            $table->index('product_id');

            // Foreign keys
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
