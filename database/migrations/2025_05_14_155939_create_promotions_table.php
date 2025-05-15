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
        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // id int [pk, increment]

            $table->uuid('store_id');
            $table->string('title', 255);
            $table->text('description')->nullable();

            $table->decimal('discount_percent', 5, 2); // Ví dụ: 10.00 (tương đương 10%)
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->enum('status', ['active', 'inactive'])->default('inactive');

            $table->timestamps();

            // Index
            $table->index('store_id');
            $table->index(['status', 'start_date', 'end_date']);

            // Foreign key
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
