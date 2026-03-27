<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Movie Ticket Booking Model
    |--------------------------------------------------------------------------
    |
    | This model serves as the primary record for a movie reservation. 
    | It stores transaction details, payment status, and links a 
    | registered user to their specific showtime and selected seats.
    |
    */

    protected $fillable = ['user_id', 'showtime_id', 'reference_number', 'payment_method', 'total_price', 'status'];

    public function user() { return $this->belongsTo(User::class); }

    public function showtime() { return $this->belongsTo(Showtime::class); }

    public function seats() { return $this->hasMany(BookedSeat::class); }
}