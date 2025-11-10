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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');

            // USERS (default id)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // VENUES (venue_id)
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->foreign('venue_id')
                ->references('venue_id')
                ->on('venues')
                ->onDelete('cascade');

            // EVENTS (event_id)
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->onDelete('cascade');

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->enum('status', ['pending', 'approved', 'cancelled', 'completed'])->default('pending');
            $table->decimal('total_cost', 10, 2)->nullable();

            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
