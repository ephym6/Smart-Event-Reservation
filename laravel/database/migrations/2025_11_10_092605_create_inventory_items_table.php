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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('venue_id')->nullable();
            $table
                ->foreign('venue_id')
                ->references('venue_id')
                ->on('venues')
                ->onDelete('cascade');
            $table->string('item_name', 100);
            $table->integer('quantity_available')->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
