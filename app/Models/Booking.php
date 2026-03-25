<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'showtime_id', 'reference_number', 'payment_method', 'total_price', 'status'];

    public function user() { return $this->belongsTo(User::class); }

    public function showtime() { return $this->belongsTo(Showtime::class); }

    public function seats() { return $this->hasMany(BookedSeat::class); }
}
