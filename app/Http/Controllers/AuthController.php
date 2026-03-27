<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
        ]);

        return redirect('/login')->with('success', 'Registered successfully. Please login.');
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            
            $isAdminRoute = $request->is('admin-login') || Str::contains(url()->previous(), 'admin-login');

            if ($isAdminRoute && $user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Unauthorized. Admin access only.'])->onlyInput('email');
            }

            $request->session()->regenerate();

            return ($user->role === 'admin') 
                ? redirect()->intended('/admin/dashboard') 
                : redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    /*
    |--------------------------------------------------------------------------
    | Admin-Specific Secure Login
    |--------------------------------------------------------------------------
    */

    public function showAdminLogin() 
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout(); 
                return back()->withErrors(['email' => 'Access denied. Admin privileges required.'])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid admin credentials.'])->onlyInput('email');
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