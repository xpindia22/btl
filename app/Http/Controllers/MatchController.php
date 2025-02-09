<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches; // Updated model name to Matches

class MatchController extends Controller
{
    public function createSingles()
    {
        return view('matches.create_singles');
    }

    public function editSingles()
    {
        return view('matches.edit_singles');
    }

    public function createBoysDoubles()
    {
        return view('matches.create_boys_doubles');
    }

    public function editBoysDoubles()
    {
        return view('matches.edit_boys_doubles');
    }

    public function editAllDoubles()
    {
        return view('matches.edit_all_doubles');
    }
    
    public function index()
    {
        $matches = Matches::all(); // Updated model reference
        return view('matches.index', compact('matches')); // Pass data to Blade
    }
}
