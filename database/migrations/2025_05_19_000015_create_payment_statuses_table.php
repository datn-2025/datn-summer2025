<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary(); // This creates an auto-incrementing primary key
            $table->string('name', 100);
            $table->timestamps();

            // Add index for name lookups
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_statuses');
    }
};
