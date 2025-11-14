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
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id')->nullable()->index();
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('party_id')
                  ->references('id')
                  ->on('parties')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Avoid dropping the named foreign key directly because its name
        // may not exist in all databases/environments and can cause errors
        // during rollback. Dropping the table will remove any foreign keys
        // automatically in MySQL.
        Schema::dropIfExists('designs');
    }
};
