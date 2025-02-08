<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Display a list of rankings.
     */
    public function index()
    {
        return view('rankings.index'); // Ensure this view exists
    }

    /**
     * Show rankings for singles.
     */
    public function singles()
    {
        return view('rankings.singles'); // Ensure this view exists
    }

    /**
     * Show rankings for doubles.
     */
    public function doubles()
    {
        return view('rankings.doubles'); // Ensure this view exists
    }
}
