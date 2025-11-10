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
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id('reset_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table
                ->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('otp_code', 10);
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_used')->default(false);
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
