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
        Schema::table('purchase_order_pallet_designs', function (Blueprint $table) {
            $table->decimal('pallet_size', 10, 2)->nullable()->after('quantity');
            $table->decimal('pallet_no', 10, 2)->nullable()->after('pallet_size');
            $table->decimal('total_qty', 10, 2)->nullable()->after('pallet_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_pallet_designs', function (Blueprint $table) {
            $table->dropColumn(['pallet_size', 'pallet_no', 'total_qty']);
        });
    }
};
