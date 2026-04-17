<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 
        'showtime_id', 
        'reference_number', 
        'payment_method', 
        'payment_receipt',
        'total_price', 
        'status',
        'requested_seats',
        'cancellation_reason'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(BookedSeat::class);
    }

    public function bookedSeats(): HasMany
    {
        return $this->hasMany(BookedSeat::class, 'booking_id');
    }
}