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
        $tables = [
            'parties', 'designs', 'finishes', 'sizes', 
            'purchase_orders', 'purchase_order_items', 'purchase_order_batches', 'purchase_order_pallets', 
            'dispatches', 'stock_pallets', 'purchase_order_pallet_designs', 
            'purchase_order_item_transactions', 'pallets'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'company_id')) {
                        $table->unsignedBigInteger('company_id')->nullable()->after('id');
                        $table->foreign('company_id')->references('id')->on('company_details')->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'parties', 'designs', 'finishes', 'sizes', 
            'purchase_orders', 'purchase_order_items', 'purchase_order_batches', 'purchase_order_pallets', 
            'dispatches', 'stock_pallets', 'purchase_order_pallet_designs', 
            'purchase_order_item_transactions', 'pallets'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'company_id')) {
                        $table->dropForeign(['company_id']);
                        $table->dropColumn('company_id');
                    }
                });
            }
        }
    }
};
