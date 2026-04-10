<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;

// Admin Namespace Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CinemaHallController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ShowtimeController;

/*
|--------------------------------------------------------------------------
| 1. Guest & Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies/{movie}/{date?}', [MovieController::class, 'show'])->name('movies.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| 2. Protected Customer Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Account & Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');

    // Booking & Seat Selection
    Route::get('/book/{showtime_id}', [MovieController::class, 'showSeatMap'])->name('book.seats');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    
    // Checkout Flow
    Route::post('/checkout/payment', [BookingController::class, 'showPayment'])->name('checkout.payment');
    Route::get('/checkout/payment', fn() => redirect()->route('home')); // Fallback for GET access
    Route::get('/checkout/success/{reference}', [BookingController::class, 'success'])->name('checkout.success');
});

/*
|--------------------------------------------------------------------------
| 3. Admin Panel Routes (Auth + Admin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Reports
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales-report', [DashboardController::class, 'salesReport'])->name('reports.sales');
    Route::get('/sales-report/download', [DashboardController::class, 'downloadPDF'])->name('reports.download');

    // Specific Movie Actions
    Route::patch('movies/{movie}/toggle-archive', [AdminMovieController::class, 'toggleArchive'])->name('movies.toggle-archive');
    Route::get('showtimes/movie/{movie}', [ShowtimeController::class, 'showByMovie'])->name('showtimes.movie');

    // Specific Booking Actions
    Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');

    // Resource Controllers
    Route::resource('movies', AdminMovieController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('cinema-halls', CinemaHallController::class);
    Route::resource('bookings', AdminBookingController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('showtimes', ShowtimeController::class);
});