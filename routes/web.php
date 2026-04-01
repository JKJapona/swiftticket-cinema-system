<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CinemaHallController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ShowtimeController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies/{movie}/{date?}', [MovieController::class, 'show'])->name('movies.show');

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATION ROUTES (Universal)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| 3. PROTECTED CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');

    Route::get('/book/{showtime_id}', [MovieController::class, 'showSeatMap'])->name('book.seats');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    
    Route::post('/checkout/payment', [BookingController::class, 'showPayment'])->name('checkout.payment');
    Route::get('/checkout/success/{reference}', [BookingController::class, 'success'])->name('checkout.success');
    
    Route::get('/checkout/payment', fn() => redirect()->route('home'));
});

/*
|--------------------------------------------------------------------------
| 4. ADMIN PANEL ROUTES (Admin Middleware Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::patch('movies/{movie}/toggle-archive', [AdminMovieController::class, 'toggleArchive'])
        ->name('movies.toggle-archive');
        
    Route::resource('movies', AdminMovieController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::get('showtimes/movie/{movie}', [ShowtimeController::class, 'showByMovie'])
        ->name('showtimes.movie');

    Route::resource('cinema-halls', CinemaHallController::class);
    Route::resource('bookings', AdminBookingController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('showtimes', ShowtimeController::class);

});