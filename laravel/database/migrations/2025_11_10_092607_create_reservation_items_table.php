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
        Schema::create('reservation_items', function (Blueprint $table) {
            $table->id('res_item_id');
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table
                ->foreign('reservation_id')
                ->references('reservation_id')
                ->on('reservations')
                ->onDelete('cascade');

            $table->unsignedBigInteger('item_id')->nullable();
            $table
                ->foreign('item_id')
                ->references('item_id')
                ->on('inventory_items')
                ->onDelete('cascade');
            $table->integer('quantity_reserved')->nullable();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_items');
    }
};
