<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CinemaHall extends Model
{
    protected $table = 'cinema_halls';
    
    protected $fillable = [
        'name', 
        'screen_type', 
        'audio_system', 
        'number_of_rows', 
        'seats_per_row', 
        'status'
    ];

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class, 'hall_id');
    }

    public function getTotalSeatsAttribute(): int
    {
        return $this->number_of_rows * $this->seats_per_row;
    }
}