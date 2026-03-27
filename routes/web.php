<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies/{movie}/{date?}', [MovieController::class, 'show'])->name('movies.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
// User Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// User Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Admin Authentication
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

// Global Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Protected User Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/book/{showtime_id}', [MovieController::class, 'showSeatMap'])->name('book.seats');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/checkout/payment', function() {
        return redirect()->route('home');
    });
});

/*
|--------------------------------------------------------------------------
| Booking & Checkout Routes
|--------------------------------------------------------------------------
*/
Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/checkout/payment', [BookingController::class, 'showPayment'])->name('checkout.payment');
Route::get('/checkout/success/{reference}', [BookingController::class, 'success'])->name('checkout.success');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Admin Middleware Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Future Admin tasks like movie/user management go here
    // Route::resource('movies', AdminMovieController::class);
});