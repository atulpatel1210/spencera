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
        Schema::create('pallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_item_id');
            $table->integer('box_pallet')->default(0);
            $table->integer('total_pallet')->default(0);
            $table->integer('total_boxe_pallets')->default(0);

            $table->timestamps();

            $table->foreign('purchase_order_item_id')
                ->references('id')->on('purchase_order_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallets');
    }
};
