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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('party_name');
            $table->string('party_type');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('address')->nullable();
            $table->string('gst_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
