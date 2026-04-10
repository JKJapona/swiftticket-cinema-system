<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
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

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        $customer->load('bookings.showtime.movie');

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * API: Return customer details with relations for the modal.
     */
    public function apiShow(User $customer)
    {
        $customer->load('bookings.showtime.movie');
        return response()->json($customer);
    }

    /**
     * Toggle customer status between active and banned.
     */
    public function toggleStatus(User $customer)
    {
        $customer->status = ($customer->status === 'active') ? 'banned' : 'active';
        $customer->save();

        $message = $customer->status === 'banned' 
            ? "{$customer->full_name} has been banned." 
            : "{$customer->full_name} has been reinstated.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Internal Helper: Total count of customers.
     */
    private function getTotalCustomersCount()
    {
        return User::where('role', 'customer')->count();
    }

    /**
     * Internal Helper: Customers with bookings in the current month.
     */
    private function getActiveCustomersThisMonthCount()
    {
        return User::whereHas('bookings', function ($query) {
            $query->whereMonth('created_at', now()->month);
        })->count();
    }
}