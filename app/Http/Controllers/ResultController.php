<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class ResultController extends Controller
{
    public function index()
    {
        $this->authorize('viewAnyResults', Result::class);
        return view('results.index');
    }

    public function bd()
    {
        $this->authorize('viewAnyResults', Result::class);
        return view('results.bd');
    }

    public function xd()
    {
        $this->authorize('viewAnyResults', Result::class);
        return view('results.xd');
    }

    public function singles()
    {
        $this->authorize('viewAnyResults', Result::class);
        return view('results.singles');
    }

    public function update(Request $request, Result $result)
    {
        $this->authorize('updateResults', $result);

        $request->validate([
            'score' => 'required|integer',
        ]);

        $result->update($request->all());

        return redirect()->route('results.index')->with('success', 'Result updated successfully.');
    }
}
