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
        Schema::create('tickets_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', length:50);
            $table->mediumInteger('price')->unsigned();
            $table->integer('quantity')->unsigned();;
            $table->integer('real_quantity')->unsigned();;
            $table->integer('total_quantity')->unsigned();;
            $table->mediumText('description');
            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_types');
    }
};
