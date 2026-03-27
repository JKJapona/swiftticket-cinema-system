<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    protected $fillable = ['movie_id', 'hall_id', 'show_date', 'show_time', 'price', 'total_capacity'];

    public function movie() { return $this->belongsTo(Movie::class); }
    
    public function hall() { return $this->belongsTo(CinemaHall::class, 'hall_id'); }

    public function bookings() { return $this->hasMany(Booking::class); }

    public function bookedSeats() { return $this->hasMany(BookedSeat::class, 'showtime_id'); }
}
