<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Automated Booking Cleanup Schedule
|--------------------------------------------------------------------------
|
| This scheduled task runs hourly to maintain system integrity by identifying
| 'pending' bookings that have exceeded the 24-hour payment window. 
| Expired bookings are marked as 'cancelled', and their associated seat 
| reservations are deleted to release inventory back into the available pool.
|
*/

Schedule::call(function () {
    $expiredBookings = Booking::where('status', 'pending')
        ->where('created_at', '<=', Carbon::now()->subHours(24))
        ->get();

    foreach ($expiredBookings as $booking) {
        $booking->update(['status' => 'cancelled']);

        $booking->bookedSeats()->delete(); 
    }
})->hourly();