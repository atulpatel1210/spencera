<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_order_batches', function (Blueprint $table) {
            $table->integer('remaining_qty')->default(0)->after('qty');
        });

        // Initialize remaining_qty
        $batches = DB::table('purchase_order_batches')->get();
        foreach ($batches as $batch) {
            $packedQty = DB::table('purchase_order_pallet_designs')->where('batch_id', $batch->id)->sum('quantity');
            DB::table('purchase_order_batches')->where('id', $batch->id)->update([
                'remaining_qty' => $batch->qty - $packedQty
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_batches', function (Blueprint $table) {
            $table->dropColumn('remaining_qty');
        });
    }
};
