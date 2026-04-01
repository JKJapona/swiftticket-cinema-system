<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Movie Showtime Schedule Model
    |--------------------------------------------------------------------------
    |
    | This model acts as the junction between a specific movie and a cinema 
    | hall. It defines the scheduling, pricing, and capacity constraints 
    | required to facilitate ticket sales and seat availability checks.
    |
    */

    protected $fillable = [ 'movie_id', 'hall_id', 'show_date', 'show_time', 'price', 'total_capacity', 'booked_seats' ];

    public function movie() { return $this->belongsTo(Movie::class); }
    
    public function hall() { return $this->belongsTo(CinemaHall::class, 'hall_id'); }

    public function bookings() { return $this->hasMany(Booking::class); }

    public function bookedSeats() { return $this->hasMany(BookedSeat::class, 'showtime_id'); }
}