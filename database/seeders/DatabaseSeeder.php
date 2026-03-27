<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        DB::table('users')->insert([
            [
                'full_name' => 'Admin User',
                'email' => 'admin@swiftticket.com',
                'password_hash' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'full_name' => 'Test Customer',
                'email' => 'test@example.com',
                'password_hash' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
            ],
        ]);

        DB::table('movies')->insert([
    [
        'title' => 'Avengers: Endgame',
        'synopsis' => 'After the devastating events of Infinity War, the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to restore order to the universe.',
        'cast_members' => 'Robert Downey Jr., Chris Evans, Mark Ruffalo, Chris Hemsworth, Scarlett Johansson',
        'genre' => 'Action',
        'runtime_minutes' => 181,
        'rating' => 'PG',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BMTc5MDE2ODcwNV5BMl5BanBnXkFtZTgwMzI2NzQ2NzM@._V1_.jpg',
        'cover_path' => 'covers/avengers-endgame.jpg',
        'trailer_url' => 'https://www.youtube.com/embed/TcMBFSGVi1c',
        'release_date' => '2019-04-26',
        'status' => 'now_showing',
    ],
    [
        'title' => 'Interstellar',
        'synopsis' => 'When Earth becomes uninhabitable, a team of ex-pilots and scientists travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
        'cast_members' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain, Michael Caine',
        'genre' => 'Sci-Fi',
        'runtime_minutes' => 169,
        'rating' => 'G',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDktN2IxOS00OGEyLWFmMjktY2FiMmZkNWIyODZiXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/embed/zSWdZVtXT7E',
        'release_date' => '2014-11-07',
        'status' => 'now_showing',
    ],
    [
        'title' => 'The Conjuring',
        'synopsis' => 'Paranormal investigators Ed and Lorraine Warren work to help a family terrorized by a dark presence in their farmhouse.',
        'cast_members' => 'Vera Farmiga, Patrick Wilson, Lili Taylor, Ron Livingston',
        'genre' => 'Horror',
        'runtime_minutes' => 112,
        'rating' => 'R-16',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BMTM3NjA1NDMyMV5BMl5BanBnXkFtZTcwMDQzNDMzOQ@@._V1_FMjpg_UX1200_.jpg',
        'cover_path' => 'covers/conjuring.jpg',
        'trailer_url' => 'https://www.youtube.com/embed/k10ETZ41q5o',
        'release_date' => '2013-07-19',
        'status' => 'now_showing',
    ],
    [
        'title' => 'Spider-Man: Across the Spider-Verse',
        'synopsis' => 'Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence.',
        'cast_members' => 'Shameik Moore, Hailee Steinfeld, Oscar Isaac, Jake Johnson',
        'genre' => 'Animation',
        'runtime_minutes' => 140,
        'rating' => 'G',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BMzI0NmVkMjEtYmY4MS00ZDMxLTlkZmEtMzU4MDQxYTMzMjU2XkEyXkFqcGdeQXVyMzQ0MzA0NTM@._V1_.jpg',
        'cover_path' => 'covers/spiderman.webp',
        'trailer_url' => 'https://www.youtube.com/embed/shW9i6k8cB0',
        'release_date' => '2023-06-02',
        'status' => 'now_showing',
    ],
    [
        'title' => 'The Batman',
        'synopsis' => 'When a sadistic serial killer begins murdering key political figures in Gotham, Batman is forced to investigate the city\'s hidden corruption.',
        'cast_members' => 'Robert Pattinson, Zoë Kravitz, Paul Dano, Jeffrey Wright',
        'genre' => 'Action',
        'runtime_minutes' => 176,
        'rating' => 'R-13',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BMmU5NGJlMzAtMGNmOC00YjJjLTgyMzUtNjAyYmE4Njg5YWMyXkEyXkFqcGc@._V1_FMjpg_UY4096_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/watch?v=mqqft2x_Aa4',
        'release_date' => '2022-03-04',
        'status' => 'now_showing',
    ],
    [
        'title' => 'John Wick: Chapter 4',
        'synopsis' => 'John Wick uncovers a path to defeating The High Table. But before he can earn his freedom, Wick must face off against a new enemy.',
        'cast_members' => 'Keanu Reeves, Donnie Yen, Bill Skarsgård, Laurence Fishburne',
        'genre' => 'Action',
        'runtime_minutes' => 169,
        'rating' => 'R-16',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BY2Q2ZmI5ZjUtNWVhMC00YzJkLTlmYjMtY2RmZDhkNzEzYjZhXkEyXkFqcGc@._V1_FMjpg_UY6001_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/watch?v=qEVUtrk8_B4',
        'release_date' => '2023-03-24',
        'status' => 'now_showing',
    ],
    [
        'title' => 'Dune: Part Two',
        'synopsis' => 'Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family.',
        'cast_members' => 'Timothée Chalamet, Zendaya, Rebecca Ferguson, Javier Bardem',
        'genre' => 'Sci-Fi',
        'runtime_minutes' => 166,
        'rating' => 'PG',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BNTc0YmQxMjEtODI5MC00NjFiLTlkMWUtOGQ5NjFmYWUyZGJhXkEyXkFqcGc@._V1_FMjpg_UY4096_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/embed/Way9Dexny3w',
        'release_date' => '2024-03-01',
        'status' => 'now_showing',
    ],
    [
        'title' => 'Deadpool & Wolverine',
        'synopsis' => 'A weary Wolverine finds himself recovering from his injuries when he crosses paths with a loudmouth Deadpool.',
        'cast_members' => 'Ryan Reynolds, Hugh Jackman, Emma Corrin',
        'genre' => 'Action',
        'runtime_minutes' => 128,
        'rating' => 'R-16',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BZTk5ODY0MmQtMzA3Ni00NGY1LThiYzItZThiNjFiNDM4MTM3XkEyXkFqcGc@._V1_FMjpg_UY3000_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/embed/73_1biulkYk',
        'release_date' => '2024-07-26',
        'status' => 'now_showing',
    ],
    [
        'title' => 'Gladiator II',
        'synopsis' => 'Years after witnessing the death of the revered hero Maximus at the hands of his uncle, Lucius is forced to enter the Colosseum.',
        'cast_members' => 'Paul Mescal, Denzel Washington, Pedro Pascal',
        'genre' => 'Action',
        'runtime_minutes' => 148,
        'rating' => 'R-13',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BZjU4OGIzMmYtZWYwYS00N2I2LWE2MjMtZWUxMmFmNjYwOWM4XkEyXkFqcGc@._V1_FMjpg_UX1080_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/embed/4rgYUipGJNo',
        'release_date' => '2024-11-22',
        'status' => 'now_showing',
    ],
    [
        'title' => 'War Machine',
        'synopsis' => 'During the final stage of US Army Ranger selection, an elite team\'s training exercise turns into a fight for survival against an unimaginable threat.',
        'cast_members' => 'Alan Ritchson, Dennis Quaid, Stephan James',
        'genre' => 'Action',
        'runtime_minutes' => 115,
        'rating' => 'R-13',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BNjFiZjk0NDItNjJiMy00YTc3LWI0OWQtMjBlNWQxYWQ2NjQzXkEyXkFqcGc@._V1_FMjpg_UY2100_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/watch?v=AFuE1LRxm80',
        'release_date' => '2026-03-06',
        'status' => 'now_showing',
    ],
    [
        'title' => 'The Mandalorian & Grogu',
        'synopsis' => 'The journey of the Mandalorian and his apprentice continues as they navigate the galaxy in a new era.',
        'cast_members' => 'Pedro Pascal, Sigourney Weaver',
        'genre' => 'Sci-Fi',
        'runtime_minutes' => 120,
        'rating' => 'G',
        'poster_path' => 'https://m.media-amazon.com/images/M/MV5BMjZiZTAwMzctMDA3OS00ZDQ1LWE0YTktMzk1MTcwZTU3ZDFjXkEyXkFqcGc@._V1_FMjpg_UX729_.jpg',
        'cover_path' => null,
        'trailer_url' => 'https://www.youtube.com/watch?v=IHWlvwu8t1w',
        'release_date' => '2026-05-22',
        'status' => 'now_showing',
    ],
]);

        // 3. Expanded Cinema Halls
DB::table('cinema_halls')->insert([
    ['name' => 'Cinema 1 (IMAX)', 'screen_type' => 'IMAX', 'number_of_rows' => 10, 'seats_per_row' => 12, 'status' => 'Active'],
    ['name' => 'Cinema 2', 'screen_type' => 'Standard', 'number_of_rows' => 8, 'seats_per_row' => 10, 'status' => 'Active'],
    ['name' => 'Cinema 3', 'screen_type' => 'Standard', 'number_of_rows' => 8, 'seats_per_row' => 10, 'status' => 'Active'],
    ['name' => 'Cinema 4 (VIP)', 'screen_type' => 'Premium', 'number_of_rows' => 5, 'seats_per_row' => 6, 'status' => 'Active'],
    ['name' => 'Cinema 5 (4DX)', 'screen_type' => '4DX', 'number_of_rows' => 8, 'seats_per_row' => 8, 'status' => 'Maintenance'],
]);

// 4. Mass Seed Showtimes
$movieIds = DB::table('movies')->pluck('id');
$hallIds = DB::table('cinema_halls')->where('status', 'Active')->pluck('id')->toArray();
$times = ['10:00:00', '13:15:00', '16:30:00', '19:45:00', '22:30:00'];

foreach ($movieIds as $movieId) {
    // Generate showtimes for the next 7 days for each movie
    for ($i = 0; $i < 7; $i++) {
        $date = Carbon::today()->addDays($i)->toDateString();
        
        // Assign 2-3 random showtimes per movie per day
        $selectedTimes = (array) array_rand(array_flip($times), rand(2, 3));

        foreach ($selectedTimes as $time) {
            $hallId = $hallIds[array_rand($hallIds)];
            
            DB::table('showtimes')->insert([
                'movie_id' => $movieId,
                'hall_id' => $hallId,
                'show_date' => $date,
                'show_time' => $time,
                // Higher price for IMAX/Premium halls
                'price' => ($hallId == 1 || $hallId == 4) ? 550.00 : 350.00,
                'total_capacity' => 120, // This could also be calculated from hall rows * seats
                'booked_seats' => 0,
                'created_at' => now(),
            ]);
        }
    }
}
    }
}