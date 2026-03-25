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
        Schema::create('cinema_halls', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->enum('screen_type', ['Standard', 'IMAX', 'Premium', '4DX'])->default('Standard');
            $table->string('audio_system', 50)->default('Dolby Atmos');
            $table->integer('number_of_rows');
            $table->integer('seats_per_row');
            // Virtual column for total seats
            $table->integer('total_seats')->virtualAs('number_of_rows * seats_per_row');
            $table->enum('status', ['Active', 'Maintenance', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cinema_halls');
    }
};
