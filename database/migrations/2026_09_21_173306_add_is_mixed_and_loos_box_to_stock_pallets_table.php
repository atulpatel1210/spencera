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
        Schema::table('stock_pallets', function (Blueprint $table) {
            $table->boolean('is_mixed')->default(false)->after('finish_id');
            $table->integer('loos_box')->default(0)->after('is_mixed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_pallets', function (Blueprint $table) {
            $table->dropColumn(['is_mixed', 'loos_box']);
        });
    }
};
