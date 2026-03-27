<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies/{movie}/{date?}', [MovieController::class, 'show'])->name('movies.show');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/book/{showtime_id}', [MovieController::class, 'showSeatMap'])->name('book.seats');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});