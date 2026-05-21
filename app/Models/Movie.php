<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Movie extends Model
{
    protected $table = 'movies';
    
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
        'is_featured',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function getPosterUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->poster_path, 'poster');
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->cover_path, 'cover');
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'archived') {
            return 'Archived';
        }

        if (!$this->release_date || $this->release_date->isFuture()) {
            return 'Coming Soon';
        }

        return 'Now Showing';
    }

    private function resolveImageUrl(?string $path, string $type): string
    {
        if (!$path) {
            return asset("images/placeholder-{$type}.svg");
        }

        return Str::startsWith($path, 'http') 
            ? $path 
            : asset('storage/' . $path);
    }
}