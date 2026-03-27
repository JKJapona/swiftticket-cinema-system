<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedSeat extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Booked Seat Model
    |--------------------------------------------------------------------------
    |
    | This model represents the individual seats reserved within a booking.
    | It links specific seat codes to a showtime and a parent booking 
    | record, ensuring seat availability is tracked accurately.
    |
    */

    public $timestamps = false;

    protected $fillable = ['booking_id', 'showtime_id', 'seat_code'];

    public function booking() { return $this->belongsTo(Booking::class); }
}