<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
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
