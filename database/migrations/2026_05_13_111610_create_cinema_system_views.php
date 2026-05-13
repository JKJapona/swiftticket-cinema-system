<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW cinema_hall_analytics_view AS
            SELECT 
                *,
                (number_of_rows * seats_per_row) as calculated_total_seats
            FROM cinema_halls
        ");

        DB::statement("
            CREATE OR REPLACE VIEW booking_analytics_view AS
            SELECT 
                (SELECT SUM(total_price) FROM bookings WHERE status = 'confirmed') as total_revenue,
                (SELECT COUNT(*) FROM bookings WHERE status = 'pending') as pending_count,
                (SELECT COUNT(*) FROM bookings WHERE status = 'confirmed') as confirmed_count,
                (SELECT COUNT(*) FROM bookings WHERE status = 'change_requested') as change_requests_count
        ");

        DB::statement("
            CREATE OR REPLACE VIEW movie_details_view AS
            SELECT 
                m.*,
                (SELECT COUNT(*) FROM showtimes s 
                 WHERE s.movie_id = m.id 
                 AND s.show_date >= CURRENT_DATE) as active_showtimes_count,
                CASE 
                    WHEN m.status = 'archived' THEN 'Archived'
                    WHEN m.status = 'now_showing' THEN 'Now Showing'
                    WHEN m.status = 'coming_soon' THEN 'Coming Soon'
                    ELSE 'TBA'
                END as formatted_status
            FROM movies m
        ");

        DB::statement("
            CREATE OR REPLACE VIEW showtime_analytics_view AS
            SELECT 
                s.*,
                m.title as movie_title,
                m.runtime_minutes,
                h.name as hall_name,
                (s.booked_seats / NULLIF(s.total_capacity, 0)) as occupancy_rate,
                (s.booked_seats >= s.total_capacity) as is_full_status
            FROM showtimes s
            JOIN movies m ON s.movie_id = m.id
            JOIN cinema_halls h ON s.hall_id = h.id
        ");

        DB::statement("
            CREATE OR REPLACE VIEW customer_analytics_view AS
            SELECT 
                u.*,
                (SELECT COUNT(*) FROM bookings b WHERE b.user_id = u.id) as total_bookings_count,
                (SELECT MAX(created_at) FROM bookings b WHERE b.user_id = u.id) as last_booking_at,
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM bookings b 
                        WHERE b.user_id = u.id 
                        AND b.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    ) THEN 1 ELSE 0 
                END as active_this_month_flag,
                (u.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as is_new_signup
            FROM users u
            WHERE u.role = 'customer'
        ");

        DB::statement("
            CREATE OR REPLACE VIEW movie_sales_analytics_view AS
            SELECT 
                m.title, 
                SUM(b.total_price) as total_revenue, 
                COUNT(b.id) as tickets_sold
            FROM bookings b
            JOIN showtimes s ON b.showtime_id = s.id
            JOIN movies m ON s.movie_id = m.id
            WHERE b.status = 'confirmed'
            GROUP BY m.id, m.title
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};