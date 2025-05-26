<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('name');
            $table->text('description')->nullable()->after('is_active');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'description', 'deleted_at']);
        });
    }
};