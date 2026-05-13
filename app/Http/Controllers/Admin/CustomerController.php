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
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View Actions
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = DB::table('customer_analytics_view');

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
            'total_customers'   => $customers->count(),
            'active_this_month' => $customers->where('active_this_month_flag', 1)->count(),
            'new_signups'       => $customers->where('is_new_signup', 1)->count(),
            'banned_count'      => $customers->where('status', 'banned')->count(),
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
}