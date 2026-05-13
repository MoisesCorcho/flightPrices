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
        Schema::create('flight_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_search_id')->constrained()->cascadeOnDelete();
            $table->string('airline');
            $table->string('airline_logo')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('departure_time');
            $table->string('arrival_time');
            $table->string('duration');
            $table->string('stops')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_results');
    }
};
