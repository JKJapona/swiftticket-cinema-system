<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Booking Transaction Records
    |--------------------------------------------------------------------------
    |
    | This migration defines the 'bookings' table, which serves as the 
    | financial and status ledger for ticket purchases. It links users 
    | to showtimes, generates a unique reference number for checkout, 
    | and tracks the specific payment method used for the transaction.
    |
    */
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('showtime_id')->constrained()->onDelete('cascade');
            $table->string('reference_number', 10)->unique();
            $table->enum('payment_method', ['Pay at Cinema', 'GCash']);
            $table->string('payment_receipt')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->text('requested_seats')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'change_requested'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
