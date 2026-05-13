<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MovieSystemSeeder extends Seeder
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
                'full_name' => 'Admin Alt',
                'email' => 'admin.alt@swiftticket.com',
                'password_hash' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'full_name' => 'Test Customer',
                'email' => 'test@customer.com',
                'password_hash' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
            ],
            [
                'full_name' => 'Banned Customer',
                'email' => 'banned@customer.com',
                'password_hash' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
            ],
        ]);

        $password = Hash::make('password');
        $now = now();

        // 1. Generate 10 Users
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'full_name' => "Customer $i",
                'email' => "user$i@customer.com",
                'password_hash' => $password,
                'phone_number' => '09' . rand(100000000, 999999999),
                'role' => 'customer',
                'status' => 'active',
                'created_at' => $now,
            ];
        }
        DB::table('users')->insert($users);

        // Developer Curated Movies - Will rely on the internet to fetch posters and covers.
        DB::table('movies')->insert([
            [
                'title' => 'The Super Mario Galaxy Movie',
                'synopsis' => 'Having thwarted Bowser\'s previous plot to marry Princess Peach, Mario and Luigi now face a fresh threat in Bowser Jr., who is determined to liberate his father from captivity and restore the family legacy. Alongside companions new and old, the brothers travel across the stars to stop the young heir\'s crusade.',
                'cast_members' => 'Chris Pratt, Charlie Day, Anya Taylor-Joy, Jack Black',
                'genre' => 'Animation',
                'runtime_minutes' => 105,
                'rating' => 'G',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/eJGWx219ZcEMVQJhAgMiqo8tYY.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/9Z2uDYXqJrlmePznQQJhL6d92Rq.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=En5QZmL5R1s',
                'release_date' => '2026-04-03',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'Project Hail Mary',
                'synopsis' => 'Science teacher Ryland Grace wakes up on a spaceship light years from home with no recollection of who he is or how he got there. As his memory returns, he begins to uncover his mission: solve the riddle of the mysterious substance causing the sun to die out. He must call on his scientific knowledge and unorthodox ideas to save everything on Earth from extinction… but an unexpected friendship means he may not have to do it alone.',
                'cast_members' => 'Ryan Gosling, Sandra Hüller',
                'genre' => 'Sci-Fi',
                'runtime_minutes' => 132,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w500/yihdXomYb5kTeSivtFndMy5iDmf.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/xQRNN12LePQqqIbhP6xP5X212HG.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=P0XN3-n-2Lo',
                'release_date' => '2026-03-20',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'Toy Story 5',
                'synopsis' => 'When Bonnie receives a Lilypad tablet as a gift and becomes obsessed, Buzz, Woody, Jessie and the rest of the gang\'s jobs become exponentially harder when they have to go head to head with the all-new threat to playtime.',
                'cast_members' => 'Tom Hanks, Tim Allen',
                'genre' => 'Family',
                'runtime_minutes' => 98,
                'rating' => 'G',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/dWIAOC9EKFehGs3CYvDQih3hxaG.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/yCXvf4C9cKnTSOJtuNRGqB0CUFV.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=c51ND9Hdbw0',
                'release_date' => '2026-06-19',
                'status' => 'coming_soon',
                'is_featured' => true,
            ],
            [
                'title' => 'Avatar: Fire and Ash',
                'synopsis' => 'In the wake of the devastating war against the RDA and the loss of their eldest son, Jake Sully and Neytiri face a new threat on Pandora: the Ash People, a violent and power-hungry Na\'vi tribe led by the ruthless Varang. Jake\'s family must fight for their survival and the future of Pandora in a conflict that pushes them to their emotional and physical limits.',
                'cast_members' => 'Sam Worthington, Zoe Saldaña, Michelle Yeoh',
                'genre' => 'Sci-Fi',
                'runtime_minutes' => 162,
                'rating' => 'PG',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/cf7hE1ifY4UNbS25tGnaTyyDrI2.jpg', // Standard high-res TMDB format
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/cp35jcJ09YyYt0QDjNsuPKcxZw6.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=nb_fFj_0rq8',
                'release_date' => '2025-12-19',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'Avengers: Doomsday',
                'synopsis' => 'Beloved heroes from three distinct universes are set on a deadly collision course and face an existential threat unlike anything they\'ve ever encountered.',
                'cast_members' => 'Robert Downey Jr., Pedro Pascal, Vanessa Kirby',
                'genre' => 'Action',
                'runtime_minutes' => 162,
                'rating' => 'R-13',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/s2Fkuq0tj7mjAHEdbfQkFkdbeRI.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/iA4mbnxs58l97r5yu44PzAsMi83.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=399Ez7WHK5s',
                'release_date' => '2026-12-18',
                'status' => 'coming_soon',
                'is_featured' => false,
            ],
            [
                'title' => 'Supergirl',
                'synopsis' => 'When an unexpected and ruthless adversary strikes too close to home, Kara Zor-El, aka Supergirl, reluctantly joins forces with an unlikely companion on an epic, interstellar journey of vengeance and justice.',
                'cast_members' => 'Milly Alcock',
                'genre' => 'Action',
                'runtime_minutes' => 128,
                'rating' => 'R-13',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/lV8Yuhz36hds5dvEIwB7DThixH.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/6nlqYWDUpNem3v2bDCxQNSg41aY.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=s1-pfiVMKAs',
                'release_date' => '2026-06-26',
                'status' => 'coming_soon',
                'is_featured' => false,
            ],
            [
                'title' => 'Moana (Live Action)',
                'synopsis' => 'Prompted by a summons from the ocean, Moana departs her home island of Motunui for the first time and travels past its barrier reef. Accompanied by the demigod Maui, she undertakes a voyage aimed at recovering the well-being of her community.',
                'cast_members' => 'Catherine Laga\'aia, Dwayne Johnson',
                'genre' => 'Adventure',
                'runtime_minutes' => 118,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/kiOlBfrTDiI6dUl0WEkOr2oP6Zj.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w780/ogv0eTxwCVcrfKYsl2GKH4hNHJL.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=n7f6hlKsxxo',
                'release_date' => '2026-07-10',
                'status' => 'coming_soon',
                'is_featured' => false,
            ],
            [
                'title' => 'Dune: Part 3',
                'synopsis' => 'Paul Atreides faces the consequences of his rise to power as a conspiracy begins to form within his own empire.',
                'cast_members' => 'Timothée Chalamet, Zendaya, Florence Pugh',
                'genre' => 'Sci-Fi',
                'runtime_minutes' => 148,
                'rating' => 'R-13',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/4mTXkCtz75P4VjbaxRJzTVZkUxK.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/5jnVGQXsc0oXLlWD9q6KuwacWQ2.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=3_9vCamtuPY',
                'release_date' => '2026-12-18',
                'status' => 'coming_soon',
                'is_featured' => false,
            ],
            [
                'title' => 'Michael',
                'synopsis' => 'Discover the story of Michael Jackson, one of the most influential artists the world has ever known, and his life beyond the music, tracing his journey from the discovery of his extraordinary talent as the lead of the Jackson Five, to the visionary artist whose creative ambition fueled a relentless pursuit to become the biggest entertainer in the world, highlighting both his life off-stage and some of the most iconic performances from his early solo career.',
                'cast_members' => 'Jaafar Jackson, Colman Domingo, Nia Long',
                'genre' => 'Biography',
                'runtime_minutes' => 155,
                'rating' => 'R-13',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/3Qud19bBUrrJAzy0Ilm8gRJlJXP.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/xh3v9v8RRbJgjcubewQ0wBjp8Qv.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=3zOLzsbOleM',
                'release_date' => '2026-04-18',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'The Cat in the Hat',
                'synopsis' => 'Doing what he does best, the Cat spreads joy to kids in his hilarious, signature, and singularly irreverent way, transporting them and audiences on a fantastical journey through a world they\'ve never seen before. Our hero takes on his toughest assignment yet for the I.I.I.I. (Institute for the Institution of Imagination and Inspiration, LLC) to cheer up Gabby and Sebastian, a pair of siblings struggling to move to a new town. Known for taking things too far, this could be this agent of chaos\' last chance to prove himself...or lose his magical hat!',
                'cast_members' => 'Bill Hader, Quinta Brunson, Bowen Yang',
                'genre' => 'Animation',
                'runtime_minutes' => 92,
                'rating' => 'G',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/tYH4rQZlHtuFSeDS0bsV17khvJC.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/pFLeuwKNJLC9ETW17uBaUwTWxWl.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=jz8pLlPhSeY',
                'release_date' => '2026-03-06',
                'status' => 'now_showing',
                'is_featured' => false,
            ],
            [
                'title' => 'Shrek 5',
                'synopsis' => 'The beloved ogre and his family return for a new adventure that explores the origins of Far Far Away.',
                'cast_members' => 'Mike Myers, Eddie Murphy, Cameron Diaz',
                'genre' => 'Animation',
                'runtime_minutes' => 100,
                'rating' => 'G',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/aRntI6gNBMu4B6YozI7rxXRPyQo.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/tgiuYuRg0vKh8OSjxbl1DzT9BfI.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=KbiwL74KyJQ',
                'release_date' => '2026-07-01',
                'status' => 'coming_soon',
                'is_featured' => false,
            ],
            [
                'title' => 'Tron: Ares',
                'synopsis' => 'A highly sophisticated Program, Ares, is sent from the digital world into the real world on a dangerous mission, marking humankind’s first encounter with A.I. beings.',
                'cast_members' => 'Jared Leto, Greta Lee, Evan Peters',
                'genre' => 'Sci-Fi',
                'runtime_minutes' => 145,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/vV8BRsa5h88RsmP6fpqKDzGElt7.jpg', 
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/jJwPQhQt2Y1B5qaa0W8m0CPO1m4.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=YShVEXb7-ic',
                'release_date' => '2025-12-19',
                'status' => 'now_showing',
                'is_featured' => false,
            ],
            [
                'title' => 'Wicked: Part Two',
                'synopsis' => 'As an angry mob rises against the Wicked Witch, Glinda and Elphaba will need to come together one final time. With their singular friendship now the fulcrum of their futures, they will need to truly see each other, with honesty and empathy, if they are to change themselves, and all of Oz, for good.',
                'cast_members' => 'Cynthia Erivo, Ariana Grande, Michelle Yeoh, Jeff Goldblum',
                'genre' => 'Fantasy',
                'runtime_minutes' => 135,
                'rating' => 'PG',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/u7x3I5V9aP4tMZACmM8ClY8lK6K.jpg', 
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/u6NZLraa4QjN1I64pgSKoC5jr8u.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=Gjb3JRmV3rA',
                'release_date' => '2025-11-26',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'Beyond the Spider-Verse',
                'synopsis' => 'Miles Morales journeys across the multiverse to find the only version of himself that can stop The Spot.',
                'cast_members' => 'Shameik Moore, Hailee Steinfeld, Oscar Isaac',
                'genre' => 'Animation',
                'runtime_minutes' => 140,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/7j03O1XEdvYUxN2oWXOxjEJicwN.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/fUcT8bcJD3SZsy84Z9QRP6kOS7v.jpg',
                'trailer_url' => null,
                'release_date' => '2026-03-27',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'Horizon: An American Saga - Chapter 3',
                'synopsis' => 'Kevin Costner\'s sprawling chronicle of the pre-and-post-Civil War expansion of the American West.',
                'cast_members' => 'Kevin Costner, Sienna Miller',
                'genre' => 'Western',
                'runtime_minutes' => 170,
                'rating' => 'R-16',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/uozBQ0BMjTVbvLsv8ZXgKSJRQxv.jpghttps://media.themoviedb.org/t/p/w500/6vS6D00mAnuUf646066rS6Yh.jpg',
                'cover_path' => null,
                'trailer_url' => null,
                'release_date' => '2026-04-10',
                'status' => 'now_showing',
                'is_featured' => false,
            ],
            [
                'title' => 'The Hunger Games: Sunrise on the Reaping',
                'synopsis' => 'Focusing on the 50th Hunger Games, the story of Haymitch Abernathy and his struggle to survive.',
                'cast_members' => 'TBA',
                'genre' => 'Action',
                'runtime_minutes' => 135,
                'rating' => 'R-13',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/jNcZH8yplxttRlf0wwqfGGMKHxX.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/5h9vKBzB6iOde9k6rGpLJ4hyIdm.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=fS35YSjopjE',
                'release_date' => '2026-11-20',
                'status' => 'coming_soon',
                'is_featured' => true,
            ],
            [
                'title' => '18th Rose',
                'synopsis' => 'A spirited teen dreaming of the perfect debut makes a deal with a lonely newcomer, but unexpected feelings and revelations may shatter their plans.',
                'cast_members' => 'Xyriel Manabat, Kyle Echarri, Nikki Valdez, Cris Villanueva',
                'genre' => 'Romance',
                'runtime_minutes' => 131,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/6pUwaXT6tdA6sek8o6SdFYudJDj.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/21YKck5bSRcvRIXlVg6ZaV0k0sj.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=7ocRU1SGX7E',
                'release_date' => '2026-04-09',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'Street Fighter',
                'synopsis' => 'In 1993, estranged Street Fighters Ryu and Ken Masters are thrown back into combat when the mysterious Chun-Li recruits them for the next World Warrior Tournament: a brutal clash of fists, fate, and fury. But behind this battle royale lies a deadly conspiracy that forces them to face off against each other and the demons of their past. And if they don\'t, it\'s GAME OVER!',
                'cast_members' => 'TBA',
                'genre' => 'Action',
                'runtime_minutes' => 115,
                'rating' => 'R-13',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/wOnoOeDcsLSo72ON1UC6c1VHKk1.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/1BENoMPde82MiPqMHQmOppU75rD.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=Xt4X4FvXk2A',
                'release_date' => '2026-10-14',
                'status' => 'coming_soon',
                'is_featured' => true,
            ],
            [
                'title' => 'Backrooms',
                'synopsis' => 'A strange doorway appears in the basement of a furniture showroom.',
                'cast_members' => 'Chiwetel Ejiofor (Clark), Renate Reinsve (Dr. Mary Kline), Mark Duplass, Finn Bennett, Lukita Maxwell, Avan Jogia',
                'genre' => 'Horror',
                'runtime_minutes' => 98,
                'rating' => 'R-13',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/vpkNMkbisv5cTaIfCzUduYzXnjb.jpg', 
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/mCpwRayjXMFzKHbjbzc5JRKfq1O.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=0HjdiohVOik',
                'release_date' => '2026-05-29',
                'status' => 'coming_soon',
                'is_featured' => true,
            ],
            [
                'title' => 'The Drama',
                'synopsis' => 'A happily engaged couple is put to the test when an unexpected turn sends their wedding week off the rails.',
                'cast_members' => 'Zendaya, Robert Pattinson, Alana Haim, Mamoudou Athie, Hailey Gates',
                'genre' => 'Romance',
                'runtime_minutes' => 118,
                'rating' => 'R-16',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/ikcNOWB6Qo1ER1H1BJL6Vf0W22s.jpg', 
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/1oKLEA9JOhvaBwLpqjROisvWMy7.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=6zmKcUa4Xxk',
                'release_date' => '2026-04-03',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'Eddington',
                'synopsis' => 'During the pandemic, a couple driving through New Mexico runs out of gas just outside the small town of Eddington. While initially greeted with warmth, the town’s atmospheric charm quickly turns into a nightmare as sun-drenched paranoia takes hold.',
                'cast_members' => 'Joaquin Phoenix, Pedro Pascal, Emma Stone, Austin Butler, Luke Grimes, Deirdre O\'Connell',
                'genre' => 'Western',
                'runtime_minutes' => 142,
                'rating' => 'R-18',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/4GIqZUgPZ146BhibsPHMHef2nXX.jpg', 
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/5UtcdW6vLonald1XF0zejg3kAnR.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=oL6jZqExlIk',
                'release_date' => '2026-03-20',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'The Woman in the Yard',
                'synopsis' => 'A suspenseful thriller starring Danielle Deadwyler.',
                'cast_members' => 'Danielle Deadwyler',
                'genre' => 'Horror',
                'runtime_minutes' => 95,
                'rating' => 'R-16',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/n0WS2TsNcS6dtaZKzxipyO7LuCJ.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/3lEV4CoKoeT2cZ4fbKEJugZcw6Z.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=1s-Ko4J3mWs',
                'release_date' => '2026-03-27',
                'status' => 'now_showing',
                'is_featured' => false,
            ],
            [
                'title' => 'Aang: The Last Airbender',
                'synopsis' => 'Avatar Aang, the world\'s last Airbender, learns of an ancient power that could save his culture from extinction. With the help of his friends, he embarks on a global quest to find it before it falls into the wrong hands and threatens to upend the peace they sacrificed everything to achieve.',
                'cast_members' => 'Eric Nam, Dave Bautista',
                'genre' => 'Animation',
                'runtime_minutes' => 110,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/xEoOpUCgf4k8OkYfW5CKJHYUfT8.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/sS3zGYFPcfM5pArVNWl6qLyaSmU.jpg',
                'trailer_url' => null,
                'release_date' => '2026-10-9',
                'status' => 'coming_soon',
                'is_featured' => false,
            ],
            [
                'title' => 'Hello, Love, Again',
                'synopsis' => 'Five years after Joy left Hong Kong for Canada, she and Ethan reunite in a different world. As they navigate their new lives, they must discover if their love is strong enough to bridge the distance and the changes within themselves.',
                'cast_members' => 'Kathryn Bernardo, Alden Richards',
                'genre' => 'Romance',
                'runtime_minutes' => 128,
                'rating' => 'PG',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/sUJKto0SUNPo5JLoQSWAadr2wbw.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/7HD80No4QO71recMEgmkwuZWl99.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=uRBHJPic9zc',
                'release_date' => '2025-11-13',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'The Black Phone 2',
                'synopsis' => 'The terrifying supernatural phone rings once more in this direct sequel.',
                'cast_members' => 'Ethan Hawke, Mason Thames',
                'genre' => 'Horror',
                'runtime_minutes' => 104,
                'rating' => 'R-16',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/gFddBLQ8wj9M9O82iPzgX5KVNHz.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/7QMsQ465YlyCFstLJUeUdpEwNUd.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=v0kqkRZHqk4',
                'release_date' => '2025-10-17',
                'status' => 'archived',
                'is_featured' => true,
            ],
            [
                'title' => 'I Fell, It\'s Fine',
                'synopsis' => 'A guarded wellness resort owner finds her controlled life spiraling when an otherworldly woman arrives, forcing her to confront long-buried emotions.',
                'cast_members' => 'Rhian Ramos, Glaiza De Castro',
                'genre' => 'Romance',
                'runtime_minutes' => 112,
                'rating' => 'R-16',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/1KCtVONn5VpHD6nc1orKRfYRFvA.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/e8HHThuBgIMRXpxHLwtytLC3lMh.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=LSR6lyPDHKA',
                'release_date' => '2026-04-04',
                'status' => 'now_showing',
                'is_featured' => true,
            ],
            [
                'title' => 'A Special Memory',
                'synopsis' => 'Two former lovers rediscover their connection through a series of masterclasses in loss, longing, and the persistence of memory.',
                'cast_members' => 'Bela Padilla, Carlo Aquino',
                'genre' => 'Romance',
                'runtime_minutes' => 108,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/1ucCvNfCUlhacBZseLik4IWg797.jpg',
                'cover_path' => null,
                'trailer_url' => 'https://www.youtube.com/watch?v=HT3dagVqPds',
                'release_date' => '2026-03-11',
                'status' => 'now_showing',
                'is_featured' => false,
            ],
            [
                'title' => 'Tayo Sa Wakas',
                'synopsis' => 'A journey through time that challenges a young couple to navigate the complexities of a love that refuses to fade.',
                'cast_members' => 'Donny Pangilinan, Belle Mariano',
                'genre' => 'Romance',
                'runtime_minutes' => 125,
                'rating' => 'PG',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/nm7cV28fqUvQCwFouTxjpI4EGbt.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/soS6D00mAnuUf646066rS6Yh.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=RPMwDAXA5EY',
                'release_date' => '2026-04-29',
                'status' => 'coming_soon',
                'is_featured' => false,
            ],
            [
                'title' => 'Five Nights at Freddy\'s 2',
                'synopsis' => 'One year since the supernatural nightmare at Freddy Fazbear\'s Pizza, the stories about what transpired there have been twisted into a campy local legend, inspiring the town\'s first ever Fazfest. With the truth kept from her, Abby sneaks out to reconnect with Freddy, Bonnie, Chica, and Foxy, setting into motion a terrifying series of events that will reveal dark secrets about the real origin of Freddy\'s, and unleash a decades-hidden horror.',
                'cast_members' => 'Josh Hutcherson, Matthew Lillard',
                'genre' => 'Horror',
                'runtime_minutes' => 108,
                'rating' => 'R-13',
                'poster_path' => 'https://image.tmdb.org/t/p/w780/2LGb8KyIbPlUTG6pr6WKZnTP5yR.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w1280/54BOXpX2ieTXMDzHymdDMnUIzYG.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=NQypHE9_Fm4',
                'release_date' => '2025-12-05',
                'status' => 'archived',
                'is_featured' => false,
            ],
            [
                'title' => 'Huwag Kang Titingin',
                'synopsis' => 'A group of friends enters a forbidden zone and realizes they are being hunted by a supernatural force that demands they never look back.',
                'cast_members' => 'Sofia Pablo, Allen Ansay, Marco Masa',
                'genre' => 'Horror',
                'runtime_minutes' => 96,
                'rating' => 'R-13',
                'poster_path' => 'https://media.themoviedb.org/t/p/w780/fLdWIk9c01RFXCaEKCjqIDbOe6i.jpg',
                'cover_path' => null,
                'trailer_url' => 'https://www.youtube.com/watch?v=nuxkYfZMHe0',
                'release_date' => '2026-03-04',
                'status' => 'now_showing',
                'is_featured' => false,
            ],
        ]);

        DB::table('cinema_halls')->insert([
            ['name' => 'Cinema 1 (IMAX)', 'screen_type' => 'IMAX', 'number_of_rows' => 10, 'seats_per_row' => 12, 'status' => 'Active'],
            ['name' => 'Cinema 2', 'screen_type' => 'Standard', 'number_of_rows' => 8, 'seats_per_row' => 10, 'status' => 'Active'],
            ['name' => 'Cinema 3', 'screen_type' => 'Standard', 'number_of_rows' => 8, 'seats_per_row' => 10, 'status' => 'Active'],
            ['name' => 'Cinema 4 (VIP)', 'screen_type' => 'Premium', 'number_of_rows' => 5, 'seats_per_row' => 6, 'status' => 'Active'],
            ['name' => 'Cinema 5 (4DX)', 'screen_type' => '4DX', 'number_of_rows' => 8, 'seats_per_row' => 8, 'status' => 'Maintenance'],
        ]);


        $halls = DB::table('cinema_halls')->where('status', 'Active')->get();
        $movies = DB::table('movies')
            ->where('status', 'now_showing')
            ->get()
            ->toArray();

        if (empty($movies)) {
            return;
        }

        foreach (range(0, 13) as $dayOffset) {
            $date = Carbon::today()->addDays($dayOffset)->toDateString();

            foreach ($halls as $hall) {
                $currentTime = Carbon::createFromFormat('H:i:s', '10:00:00');
                $hardClose = Carbon::createFromFormat('H:i:s', '00:30:00')->addDay();

                while (true) {
                    // Calculate how many minutes are left until 12:30 AM
                    $minutesLeftInDay = $currentTime->diffInMinutes($hardClose, false);

                    // Filter movies that can fit (Runtime + 30m buffer must be <= minutes left)
                    $eligibleMovies = collect($movies)->filter(function($movie) use ($minutesLeftInDay) {
                        return ($movie->runtime_minutes + 30) <= $minutesLeftInDay;
                    });

                    if ($eligibleMovies->isEmpty()) {
                        break;
                    }

                    // Pick a random movie from the ones that actually fit
                    $movie = $eligibleMovies->random();
                    
                    DB::table('showtimes')->insert([
                        'movie_id'       => $movie->id,
                        'hall_id'        => $hall->id,
                        'show_date'      => $date,
                        'show_time'      => $currentTime->toTimeString(),
                        'price'          => (in_array($hall->screen_type, ['IMAX', 'Premium'])) ? 550.00 : 350.00,
                        'total_capacity' => $hall->total_seats,
                        'booked_seats'   => 0,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);

                    // Advance time: Runtime + 30 mins buffer
                    $currentTime->addMinutes($movie->runtime_minutes + 30);
                    
                    // Round to nearest 5 minutes
                    $remainder = $currentTime->minute % 5;
                    if ($remainder > 0) {
                        $currentTime->addMinutes(5 - $remainder);
                    }
                }
            }
        }

        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id')->toArray();
        $showtimeIds = DB::table('showtimes')->pluck('id')->toArray();
        $methods = ['Pay at Cinema', 'GCash'];
        $bookingStatuses = ['confirmed', 'pending', 'cancelled', 'change_requested'];
        $seatTracker = [];

        // Generate 50 bookings
        for ($i = 0; $i < 50; $i++) {
            $stId = $showtimeIds[array_rand($showtimeIds)];
            $status = $bookingStatuses[array_rand($bookingStatuses)];

            $showtime = DB::table('showtimes')->where('id', $stId)->first();
            $ticketPrice = $showtime->price;

            $numSeats = 4;
            $totalPrice = $ticketPrice * $numSeats;

            if (!isset($seatTracker[$stId])) {
                $seatTracker[$stId] = DB::table('booked_seats')->where('showtime_id', $stId)->count();
            }

            if ($seatTracker[$stId] + $numSeats > $showtime->total_capacity) {
                continue;
            }

            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $customerIds[array_rand($customerIds)],
                'showtime_id' => $stId,
                'reference_number' => strtoupper(Str::random(10)),
                'payment_method' => $methods[array_rand($methods)],
                'total_price' => $totalPrice,
                'status' => $status,
                'cancellation_reason' => ($status === 'cancelled') ? "Automated stress test cancellation reason for booking $i." : null,
                'created_at' => now(),
            ]);

            // Generate 4 seats
            $seatsToBook = [];
            for ($j = 0; $j < $numSeats; $j++) {
                $currentSeatIndex = $seatTracker[$stId];

                $column = ($currentSeatIndex % 40) + 1;
                $rowLetter = chr(65 + (int)($currentSeatIndex / 40));
                
                $seatsToBook[] = [
                    'booking_id' => $bookingId,
                    'showtime_id' => $stId,
                    'seat_code' => $rowLetter . $column
                ];

                $seatTracker[$stId]++;
            }

            DB::table('booked_seats')->insert($seatsToBook);
            DB::table('showtimes')->where('id', $stId)->increment('booked_seats', $numSeats);
        }
    }
}
