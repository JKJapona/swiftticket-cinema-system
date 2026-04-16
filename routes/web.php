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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/book/{showtime_id}', [MovieController::class, 'showSeatMap'])->name('book.seats');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    
    Route::match(['get', 'post'], '/checkout/payment', [BookingController::class, 'showPayment'])->name('checkout.payment');
    Route::get('/checkout/payment', fn() => redirect()->route('home')); 
    Route::get('/checkout/success/{reference}', [BookingController::class, 'success'])->name('checkout.success');

    Route::post('/bookings/{booking}/request-change', [BookingController::class, 'requestSeatChange'])
        ->name('bookings.request-change');
        
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| 3. Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales-report', [DashboardController::class, 'salesReport'])->name('reports.sales');
    Route::get('/sales-report/download', [DashboardController::class, 'downloadPDF'])->name('reports.download');

    Route::get('bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
    Route::patch('movies/{movie}/toggle-archive', [AdminMovieController::class, 'toggleArchive'])->name('movies.toggle-archive');
    Route::get('showtimes/movie/{movie}', [ShowtimeController::class, 'showByMovie'])->name('showtimes.movie');
    Route::patch('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/customers/api/{customer}', [CustomerController::class, 'apiShow'])->name('customers.api');
    Route::patch('/customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggleStatus');

    Route::post('/bookings/{id}/approve-change', [AdminBookingController::class, 'approveChange'])
        ->name('bookings.approve-change');

    Route::resource('movies', AdminMovieController::class)->except(['show', 'create', 'edit']);
    Route::resource('cinema-halls', CinemaHallController::class)->except(['show']);
    Route::resource('bookings', AdminBookingController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::resource('showtimes', ShowtimeController::class)->except(['show']);

    Route::get('/{any}', function () {
        abort(404);
    })->where('any', '.*');

})->whereNumber(['movie', 'booking', 'customer', 'cinema_hall', 'showtime']);

/*
|--------------------------------------------------------------------------
| 4. Static Pages & Simulation
|--------------------------------------------------------------------------
*/
Route::view('/privacy-policy', 'auth.privacy-policy')->name('privacy-policy');
Route::view('/terms-conditions', 'auth.terms-conditions')->name('terms-and-conditions');
Route::view('/contact-us', 'auth.contact-us')->name('contact-us');

Route::get('/payment/gcash-simulation', function() {
    return view('customer.booking.gcash-simulation'); 
})->name('payment.gcash');