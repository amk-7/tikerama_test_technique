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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['Autre','Concert-Spectacle','DinerGala','Festival','Formation']);
            $table->string('title', length:30);
            $table->mediumText('description');
            $table->datetime('date');
            $table->string('image', length:200);
            $table->string('city', length:100);
            $table->string('address', length:200);
            $table->enum('status', ['upcoming', 'completed', 'cancelled']);
            $table->timestamp('create_on')->default('now');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
