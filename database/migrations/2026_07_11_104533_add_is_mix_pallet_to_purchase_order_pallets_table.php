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
        Schema::table('purchase_order_pallets', function (Blueprint $table) {
            $table->boolean('is_mix_pallet')->default(0)->after('batch_id');
        });

        Schema::table('purchase_order_pallet_designs', function (Blueprint $table) {
            $table->unsignedBigInteger('batch_id')->nullable()->after('finish_id');
            $table->unsignedBigInteger('purchase_order_item_id')->nullable()->after('purchase_order_pallet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_pallets', function (Blueprint $table) {
            $table->dropColumn('is_mix_pallet');
        });

        Schema::table('purchase_order_pallet_designs', function (Blueprint $table) {
            $table->dropColumn(['batch_id', 'purchase_order_item_id']);
        });
    }
};
