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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('email', length:255);
            $table->string('phone', length:20);
            $table->mediumInteger('price')->unsigned();
            $table->string('key', length:100)->unique();
            $table->string('status', ['active', 'validated', 'expired', 'cancelled']);
            $table->timestamp('create_on')->default('now');
            $table->integer('event_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('type_id')->references('id')->on('tickets_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
