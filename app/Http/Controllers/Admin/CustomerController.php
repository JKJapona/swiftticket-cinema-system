<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('bookings')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_customers'   => $this->getTotalCustomersCount(),
            'active_this_month' => $this->getActiveCustomersThisMonthCount(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        $customer->load('bookings.showtime.movie');

        return view('admin.customers.show', compact('customer'));
    }

    private function getTotalCustomersCount()
    {
        return User::where('role', 'customer')->count();
    }

    private function getActiveCustomersThisMonthCount()
    {
        return User::whereHas('bookings', function ($query) {
            $query->whereMonth('created_at', now()->month);
        })->count();
    }
}