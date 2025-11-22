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
            $table->foreignId('party_id')
                  ->constrained('parties')
                  ->onDelete('cascade');
            $table->foreignId('purchase_order_id')
                  ->constrained('purchase_orders')
                  ->onDelete('cascade');
            $table->string('po')->nullable();
            $table->foreignId('design_id')->nullable()->constrained('designs')->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
            $table->foreignId('finish_id')->nullable()->constrained('finishes')->nullOnDelete();
            $table->string('batch_no')->nullable();
            $table->unsignedBigInteger('pallet_id')->nullable();
            $table->integer('order_qty')->default(0);
            $table->integer('pending_qty')->default(0);
            $table->integer('planning_qty')->default(0);
            $table->integer('production_qty')->default(0);
            $table->integer('short_qty')->default(0);
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