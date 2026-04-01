<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration Logic
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
            'password_hash'      => Hash::make($request->password), 
            'role'          => 'customer',
            'status'        => 'active',
        ]);

        return redirect()->route('login')->with('success', 'Registered successfully. Please login.');
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication & Role-Based Redirection
    |--------------------------------------------------------------------------
    */

    public function showLogin() 
    {
        return view('auth.login');
    }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Dynamic Redirection based on user role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /*
    |--------------------------------------------------------------------------
    | Session Termination
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request) 
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}