<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CinemaHall extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Cinema Hall Structure Model
    |--------------------------------------------------------------------------
    |
    | This model defines the physical layout of a theater room. It stores
    | metadata regarding screen types and seat capacities, allowing
    | for dynamic seat map generation during the booking process.
    |
    */

    protected $fillable = ['name', 'screen_type', 'number_of_rows', 'seats_per_row', 'status'];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'hall_id');
    }
}