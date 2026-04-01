<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Movie Catalog Definition
    |--------------------------------------------------------------------------
    |
    | This migration defines the 'movies' table, which stores all cinematic 
    | metadata for SwiftTicket. It includes fields for classification ratings, 
    | media paths, and availability status (now showing vs. coming soon) 
    | to drive the front-end catalog.
    |
    */
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('synopsis')->nullable();
            $table->text('cast_members')->nullable();
            $table->string('genre', 50)->nullable();
            $table->integer('runtime_minutes')->nullable();
            $table->enum('rating', ['G', 'PG', 'R-13', 'R-16', 'R-18', 'TBA'])->default('TBA');
            $table->string('poster_path', 500)->nullable();
            $table->string('cover_path', 500)->nullable();
            $table->string('trailer_url', 255)->nullable();
            $table->date('release_date')->nullable();
            $table->enum('status', ['now_showing', 'coming_soon', 'archived'])->default('coming_soon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
