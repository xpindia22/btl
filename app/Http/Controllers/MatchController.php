<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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


     {
        public function index()
        {
            $matches = Match::all(); // Fetch all matches
            return view('matches.index', compact('matches')); // Pass data to Blade
        }
    }





}
