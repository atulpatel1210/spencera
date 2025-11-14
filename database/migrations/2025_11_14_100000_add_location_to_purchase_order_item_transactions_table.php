<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order_item_transactions', function (Blueprint $table) {
            $table->string('location')->nullable()->after('batch_no');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_order_item_transactions', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
