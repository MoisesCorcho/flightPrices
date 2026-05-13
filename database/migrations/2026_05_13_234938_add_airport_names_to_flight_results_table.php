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
        Schema::table('flight_results', function (Blueprint $table) {
            $table->string('origin_name')->nullable()->after('airline_logo');
            $table->string('destination_name')->nullable()->after('origin_name');
            $table->string('flight_number')->nullable()->after('airline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flight_results', function (Blueprint $table) {
            $table->dropColumn(['origin_name', 'destination_name', 'flight_number']);
        });
    }
};
