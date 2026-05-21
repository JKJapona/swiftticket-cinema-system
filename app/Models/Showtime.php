<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Showtime extends Model
{
    protected $table = 'showtimes';
    
    protected $fillable = [
        'movie_id', 
        'hall_id', 
        'show_date', 
        'show_time', 
        'price', 
        'total_capacity', 
        'booked_seats'
    ];

// In Showtime.php
protected $casts = [
    'show_date' => 'date:Y-m-d', // This ensures it serializes as a string correctly
    'show_time' => 'datetime:H:i',
];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
    
    public function hall(): BelongsTo
    {
        return $this->belongsTo(CinemaHall::class, 'hall_id');
    }

    public function cinemaHall(): BelongsTo
    {
        return $this->belongsTo(CinemaHall::class, 'hall_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookedSeats(): HasMany
    {
        return $this->hasMany(BookedSeat::class, 'showtime_id');
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->total_capacity - $this->booked_seats);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->booked_seats >= $this->total_capacity;
    }
}