<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Movie Showtime Scheduling
    |--------------------------------------------------------------------------
    |
    | This migration creates the 'showtimes' table, acting as a bridge between 
    | movies and cinema halls. It defines the specific date, time, and pricing 
    | for screenings, while tracking real-time capacity and occupancy to 
    | manage ticket availability.
    |
    */
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->foreignId('hall_id')->constrained('cinema_halls')->onDelete('cascade');
            $table->date('show_date');
            $table->time('show_time');
            $table->decimal('price', 10, 2)->default(350.00);
            $table->integer('total_capacity');
            $table->integer('booked_seats')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
