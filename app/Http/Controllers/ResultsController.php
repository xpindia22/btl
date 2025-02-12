<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches; // Correctly referencing the Matches model

class ResultsController extends Controller
{
    public function singles()
    {
        return view('results.singles');
    }

    public function boysDoubles()
    {
        return view('results.boys_doubles');
    }

    public function index()
    {
        $results = Matches::whereNotNull('score')->get(); // Fetch all completed matches with scores
        return view('results.index', compact('results')); // Pass data to Blade
    }
}
