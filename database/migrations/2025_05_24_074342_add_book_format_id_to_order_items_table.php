<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->char('book_format_id', 36)->nullable()->after('book_id');

            $table->foreign('book_format_id')
                ->references('id')
                ->on('book_formats')
                ->onDelete('set null');
            
            $table->index('book_format_id');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['book_format_id']);
            $table->dropIndex(['book_format_id']);
            $table->dropColumn('book_format_id');
        });
    }
};
