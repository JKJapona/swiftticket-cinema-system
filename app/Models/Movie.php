<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected $fillable = [
            'title',
            'synopsis',
            'cast_members',
            'genre',
            'runtime_minutes',
            'rating',
            'poster_path',    
            'cover_path',     
            'trailer_url',
            'release_date',
            'status',
        ];
    protected $casts = ['release_date' => 'date',];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Accessor for the Poster URL
     */
    public function getPosterUrlAttribute()
    {
        $path = $this->poster_path;

        if (!$path) {
            return asset('images/placeholder-poster.svg');
        }

        return Str::startsWith($path, 'http') 
            ? $path 
            : asset('storage/' . $path);
    }

    /**
     * Accessor for the Cover URL
     */
    public function getCoverUrlAttribute()
    {
        $path = $this->cover_path;

        if (!$path) {
            return asset('images/placeholder-cover.svg');
        }

        return Str::startsWith($path, 'http') 
            ? $path 
            : asset('storage/' . $path);
    }

    public function getDisplayStatusAttribute()
    {
        if ($this->status === 'archived') {
            return 'Archived';
        }

        if (!$this->release_date || $this->release_date->isFuture()) {
                return 'Coming Soon';
        }

        return 'Now Showing';
    }

    public function getStatusColorClassAttribute()
    {
        $status = $this->display_status;

        return match ($status) {
            'Archived'    => 'bg-secondary-soft text-secondary',
            'Coming Soon' => 'bg-warning-soft text-warning-emphasis',
            'Now Showing' => 'bg-success-soft text-success',
            default       => 'bg-light text-dark',
        };
    }

    public function getStatusIconAttribute()
    {
        $status = $this->display_status;

        return match ($status) {
            'Archived'    => 'bi-archive-fill',
            'Coming Soon' => 'bi-calendar-event',
            'Now Showing' => 'bi-play-fill',
            default       => 'bi-question-circle',
        };
    }
}