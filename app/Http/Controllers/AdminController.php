<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Administrative Dashboard 
    |--------------------------------------------------------------------------
    |
    | This controller handles the primary entry point for the administrative
    | panel. All methods within this controller are protected by the
    | 'admin' middleware defined in the routing file.
    |
    */

    public function index()
    {
        return view('admin.dashboard'); 
    }
}