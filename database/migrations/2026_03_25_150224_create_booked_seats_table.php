<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Atomic Seat Reservation Inventory
    |--------------------------------------------------------------------------
    |
    | This migration defines the 'booked_seats' table, which acts as the 
    | granular record for every occupied chair in the cinema. It enforces 
    | a composite unique constraint to provide a database-level "Hard Guard," 
    | ensuring that a specific seat code cannot be booked more than once 
    | for the same showtime.
    |
    */
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booked_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('showtime_id')->constrained()->onDelete('cascade');
            $table->string('seat_code', 5);
            $table->unique(['showtime_id', 'seat_code'], 'unique_seat_per_showtime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booked_seats');
    }
};
