<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attribute_id');
            $table->string('value', 255);
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('attribute_id')
                ->references('id')
                ->on('attributes')
                ->onDelete('cascade');

            // Add indexes for frequently accessed columns
            $table->index('attribute_id');
            $table->index('value');
            $table->index(['attribute_id', 'value']); // Composite index for searching values within an attribute
        });
    }

    public function down()
    {
        Schema::dropIfExists('attribute_values');
    }
};
