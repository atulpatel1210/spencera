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
        Schema::create('stock_pallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_id')->constrained('parties')->onDelete('cascade');
            $table->string('po')->nullable();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('purchase_order_item_id')->constrained('purchase_order_items')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('purchase_order_batches')->onDelete('cascade');
            $table->foreignId('design_id')->nullable()->constrained('designs')->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
            $table->foreignId('finish_id')->nullable()->constrained('finishes')->nullOnDelete();
            $table->string('pallet_size');
            $table->string('pallet_no');
            $table->integer('current_qty');
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->unique(['pallet_no', 'purchase_order_item_id', 'batch_id'], 'unique_pallet_item_batch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_pallets');
    }
};
