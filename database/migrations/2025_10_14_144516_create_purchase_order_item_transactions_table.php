<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_item_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_item_id')
                  ->constrained('purchase_order_items')
                  ->onDelete('cascade');

            $table->integer('quantity')->nullable();
            $table->string('batch_no')->nullable();
            $table->date('date')->nullable();
            $table->text('remark')->nullable();
            $table->string('type')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_item_transactions');
    }
};
