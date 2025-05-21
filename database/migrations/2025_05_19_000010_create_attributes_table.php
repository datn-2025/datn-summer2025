<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->softDeletes();
            $table->timestamps();

            // Add index for frequently accessed column
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attributes');
    }
};
