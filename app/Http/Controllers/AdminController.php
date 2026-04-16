<?php

namespace App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Dashboard Controller
|--------------------------------------------------------------------------
|
| This controller serves as the entry point for the administrative 
| back-end, providing an overview of system metrics and navigation.
|
*/

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard View
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.dashboard'); 
    }
}