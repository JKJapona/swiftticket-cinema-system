<?php

namespace App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Authentication & Profile Management
|--------------------------------------------------------------------------
|
| This controller handles the registration, login, and logout of users, 
| as well as managing the customer's profile and booking history.
|
*/

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration Actions
    |--------------------------------------------------------------------------
    */

    public function showRegister() 
    {
        return view('auth.register');
    }

    public function register(Request $request) 
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed'
        ]);

        User::create([
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password), 
            'role'          => 'customer',
            'status'        => 'active',
        ]);

        return redirect()->route('login')->with('success', 'Registered successfully. Please login.');
    }

    /*
    |--------------------------------------------------------------------------
    | Login & Logout Actions
    |--------------------------------------------------------------------------
    */

    public function showLogin() 
    {
        return view('auth.login');
    }

    public function login(Request $request) 
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if ($user->status === 'banned') {
            return back()->withErrors([
                'email' => 'Your account has been banned. Please contact support.',
            ])->onlyInput('email');
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return $this->authenticatedRedirection();
        }

        return back()->withErrors([
            'email' => 'Invalid email or password. Please try again.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */

    public function showProfile()
    {
        $user = Auth::user();
        $bookings = \App\Models\Booking::where('user_id', $user->id)
                    ->with(['showtime.movie'])
                    ->latest()
                    ->get();

        return view('customer.account.profile', compact('user', 'bookings'));
    }

    public function editProfile()
    {
        $user = Auth::user();

        return view('customer.account.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'full_name' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Redirection Logic
    |--------------------------------------------------------------------------
    */

    private function authenticatedRedirection()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }
}