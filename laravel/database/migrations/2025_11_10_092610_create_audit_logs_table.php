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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table
                ->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');
            $table->string('action', 255);
            $table->string('entity_type', 100)->nullable();
            $table->integer('entity_id')->nullable();
            $table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('ip_address', 45)->nullable();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
