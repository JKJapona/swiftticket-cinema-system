<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
protected $fillable = ['title', 'synopsis', 'genre', 'runtime_minutes', 'rating', 'status'];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
