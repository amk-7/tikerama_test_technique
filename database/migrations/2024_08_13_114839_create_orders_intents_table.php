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
        Schema::create('orders_intents', function (Blueprint $table) {
            $table->id('id');
            $table->mediumInteger('price')->unsigned();
            $table->string('type', length:50);
            $table->string('user_email', length:100);
            $table->string('user_phone', length:20);
            $table->datetime('expiration_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_intents');
    }
};
