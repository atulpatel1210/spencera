<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('party_id')->constrained('parties')->onDelete('cascade');;
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('po')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('design')->nullable();
            $table->string('size')->nullable();
            $table->string('finish')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('pallet')->nullable();
            $table->integer('order_qty')->nullable();
            $table->integer('pending_qty')->nullable();
            $table->integer('planning_qty')->nullable();
            $table->integer('production_qty')->nullable();
            $table->integer('short_qty')->nullable();
            $table->text('remark')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};