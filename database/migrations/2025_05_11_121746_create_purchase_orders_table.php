<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po')->nullable();
            $table->unsignedBigInteger('party_id');
            $table->string('brand_name')->nullable();
            $table->string('box_image')->nullable();
            $table->date('order_date')->nullable();
            $table->timestamps();
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};