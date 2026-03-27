<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Movie Catalog Model
    |--------------------------------------------------------------------------
    |
    | This model represents the cinematic content available in the system.
    | It stores descriptive metadata, classification ratings, and handles
    | the relationship between films and their scheduled showtimes.
    |
    */

    protected $fillable = ['title', 'synopsis', 'genre', 'runtime_minutes', 'rating', 'status'];
    protected $casts = ['release_date' => 'date',];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}