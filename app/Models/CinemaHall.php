<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CinemaHall extends Model
{
    protected $fillable = ['name', 'screen_type', 'number_of_rows', 'seats_per_row', 'status'];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'hall_id');
    }
}
