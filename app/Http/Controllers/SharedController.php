<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SharedController extends Controller
{
    /**
     * Show the Shared Access Page for Admin & User.
     */
    public function index()
    {
        return view('shared.access'); // Ensure this view exists
    }
}
