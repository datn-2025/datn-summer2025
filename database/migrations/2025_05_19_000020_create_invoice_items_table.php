<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invoice_id');
            $table->uuid('book_id');
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');

            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('restrict');

            // Add indexes for frequently accessed columns
            $table->index('invoice_id');
            $table->index('book_id');
            $table->unique(['invoice_id', 'book_id']); // Prevent duplicate books in same invoice
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
