<?php

namespace App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Customer Management Controller
|--------------------------------------------------------------------------
|
| This controller manages the "customer" user base. It handles listing with
| advanced filtering/searching, detailed account viewing, and administrative
| actions such as toggling account access (banning/reinstating).
|
*/

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View Actions
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('bookings');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total_customers'   => User::where('role', 'customer')->count(),
            'active_this_month' => User::where('role', 'customer')
                ->whereHas('bookings', function($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                })->count(),
            'new_signups'       => User::where('role', 'customer')
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
            'banned_count'      => User::where('status', 'banned')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        $customer->load('bookings.showtime.movie');

        return view('admin.customers.show', compact('customer'));
    }

    /*
    |--------------------------------------------------------------------------
    | API & Interactive Actions
    |--------------------------------------------------------------------------
    */

    public function apiShow(User $customer)
    {
        $customer->load('bookings.showtime.movie');
        return response()->json($customer);
    }

    public function toggleStatus(User $customer)
    {
        $oldStatus = $customer->status;
        $customer->status = ($oldStatus === 'active') ? 'banned' : 'active';
        $customer->save();

        if ($customer->status === 'banned') {
            return redirect()->back()->with('success', "Account for '{$customer->full_name}' has been restricted and moved to the banned list.");
        }

        return redirect()->back()->with('success', "Account for '{$customer->full_name}' has been successfully reinstated.");
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helper Logic
    |--------------------------------------------------------------------------
    */

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