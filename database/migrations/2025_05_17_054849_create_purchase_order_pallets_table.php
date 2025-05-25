<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('purchase_order_pallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->string('po')->nullable();
            $table->unsignedBigInteger('purchase_order_item_id');
            $table->string('design')->nullable();
            $table->string('size')->nullable();
            $table->string('finish')->nullable();
            $table->unsignedBigInteger('batch_id');
            $table->string('pallet_size');
            $table->string('pallet_no');
            $table->integer('total_qty');
            $table->date('packing_date');
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('purchase_order_item_id')->references('id')->on('purchase_order_items')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('purchase_order_batches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_pallets');
    }
};

