<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_order_pallet_designs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_pallet_id');
            $table->unsignedBigInteger('design_id');
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('finish_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->foreign('purchase_order_pallet_id')->references('id')->on('purchase_order_pallets')->onDelete('cascade');
            $table->foreign('design_id')->references('id')->on('designs')->onDelete('restrict');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');
            $table->foreign('finish_id')->references('id')->on('finishes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_pallet_designs');
    }
};
