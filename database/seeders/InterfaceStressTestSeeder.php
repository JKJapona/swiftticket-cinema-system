<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InterfaceStressTestSeeder extends Seeder
{
    public function run(): void
    {
        // --- PRE-CALCULATE DATA ---
        $password = Hash::make('password'); // Hash only ONCE for all users
        $now = now();

        // 1. Generate 1000 Users (Bulk)
        $users = [];
        for ($i = 1; $i <= 1000; $i++) {
            $users[] = [
                'full_name' => "User Stress Test $i",
                'email' => "user$i@test.com",
                'password_hash' => $password,
                'phone_number' => '09' . rand(100000000, 999999999),
                'role' => ($i === 1) ? 'admin' : 'customer',
                'status' => 'active',
                'created_at' => $now,
            ];
        }
        DB::table('users')->insert($users);

        // 2. Generate 1000 Movies (Bulk)
        $genres = ['Action', 'Comedy', 'Horror', 'Sci-Fi', 'Drama', 'Animation'];
        $ratings = ['G', 'PG', 'R-13', 'R-16', 'R-18'];
        $movies = [];
        for ($i = 1; $i <= 1000; $i++) {
            $movies[] = [
                'title' => "Stress Test Movie $i",
                'synopsis' => "Dummy synopsis for movie $i.",
                'cast_members' => "Actor A, Actor B, Actor C",
                'genre' => $genres[array_rand($genres)],
                'runtime_minutes' => rand(90, 181),
                'rating' => $ratings[array_rand($ratings)],
                'poster_path' => "https://picsum.photos/seed/movie" . $i . "/300/450",
                'cover_path' => "https://picsum.photos/seed/cover" . $i . "/1200/450",
                'release_date' => Carbon::now()->subMonths(rand(1, 12))->toDateString(),
                'status' => 'now_showing',
                'is_featured' => true,
                'created_at' => $now,
            ];
        }
        DB::table('movies')->insert($movies);

        // 3. Generate 1000 Cinema Halls (Bulk)
        $hallTypes = ['Standard', 'IMAX', 'Premium', '4DX'];
        $halls = [];
        for ($i = 1; $i <= 1000; $i++) {
            $halls[] = [
                'name' => "Hall $i",
                'screen_type' => $hallTypes[array_rand($hallTypes)],
                'number_of_rows' => 10,
                'seats_per_row' => 10,
                'status' => 'Active',
                'created_at' => $now,
            ];
        }
        DB::table('cinema_halls')->insert($halls);

        // 4. Create 1000 Showtimes (Bulk)
        $movieIds = DB::table('movies')->pluck('id')->toArray();
        $hallIds = DB::table('cinema_halls')->pluck('id')->toArray();
        $showtimes = [];
        for ($i = 1; $i <= 1000; $i++) {
            $showtimes[] = [
                'movie_id' => $movieIds[array_rand($movieIds)],
                'hall_id' => $hallIds[array_rand($hallIds)],
                'show_date' => Carbon::today()->addDays(rand(0, 7))->toDateString(),
                'show_time' => rand(10, 22) . ':00:00',
                'price' => 1000.00,
                'total_capacity' => 100,
                'booked_seats' => 0,
            ];
        }
        DB::table('showtimes')->insert($showtimes);

        // 5. Generate 1000 Bookings + Seats (Sequential IDs)
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id')->toArray();
        $showtimeIds = DB::table('showtimes')->pluck('id')->toArray();
        $methods = ['Pay at Cinema', 'GCash'];
        $bookingStatuses = ['confirmed', 'pending', 'cancelled', 'change_requested'];
        
        // Seat Tracker to avoid Duplicate Entry errors
        $seatTracker = [];

        for ($i = 0; $i < 1000; $i++) {
            $stId = $showtimeIds[array_rand($showtimeIds)];
            $status = $bookingStatuses[array_rand($bookingStatuses)];
            
            // Initialize tracker for this showtime if empty
            if (!isset($seatTracker[$stId])) { $seatTracker[$stId] = 1; }

            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $customerIds[array_rand($customerIds)],
                'showtime_id' => $stId,
                'reference_number' => strtoupper(Str::random(10)),
                'payment_method' => $methods[array_rand($methods)],
                'total_price' => 1000.00,
                'status' => $status,
                'cancellation_reason' => ($status === 'cancelled') ? "Automated stress test cancellation reason for booking $i." : null,
                'created_at' => $now,
            ]);

            // Assign unique sequential seats for this showtime
            $s1 = 'S' . $seatTracker[$stId]++;
            $s2 = 'S' . $seatTracker[$stId]++;

            DB::table('booked_seats')->insert([
                ['booking_id' => $bookingId, 'showtime_id' => $stId, 'seat_code' => $s1],
                ['booking_id' => $bookingId, 'showtime_id' => $stId, 'seat_code' => $s2],
            ]);

            // Update showtime capacity
            DB::table('showtimes')->where('id', $stId)->increment('booked_seats', 5);
        }
    }
}