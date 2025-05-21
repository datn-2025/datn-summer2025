<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('book_attribute_values', function (Blueprint $table) {
            $table->decimal('extra_price', 12, 2)->nullable()->after('attribute_value_id');
        });
    }

    public function down()
    {
        Schema::table('book_attribute_values', function (Blueprint $table) {
            $table->dropColumn('extra_price');
        });
    }
};
