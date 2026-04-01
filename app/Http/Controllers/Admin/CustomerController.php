<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'user')
                         ->withCount('bookings') 
                         ->latest()
                         ->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load('bookings.showtime.movie');
        return view('admin.customers.show', compact('customer'));
    }
}