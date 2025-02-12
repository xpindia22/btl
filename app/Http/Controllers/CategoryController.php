<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Optionally, restrict access to admins via middleware.
    public function __construct()
    {
        $this->middleware('auth');
        // For example: $this->middleware('admin');
    }

    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        // Get sorting parameters from the query string.
        $order_by = $request->query('order_by', 'name');
        $order_dir = $request->query('order_dir', 'desc');
        $next_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';

        // Example query – you might join with the users table if you want the creator’s name.
        // (Assuming your Category model has a 'created_by' field.)
        $categories = Category::select('categories.*')
            ->leftJoin('users', 'categories.created_by', '=', 'users.id')
            ->orderBy($order_by, $order_dir)
            ->get();

        return view('categories.index', compact('categories', 'order_by', 'order_dir', 'next_order_dir'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        // Validate input.
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'age_condition' => 'required|in:Under,Over,Between,Open',
            'age_limit1'    => 'nullable|integer',
            'age_limit2'    => 'nullable|integer',
            'sex'           => 'required|in:M,F,Mixed',
        ]);

        // Build the age_group string.
        $age_condition = $validated['age_condition'];
        $age_limit1 = $validated['age_limit1'];
        $age_limit2 = $validated['age_limit2'];

        if ($age_condition === 'Under') {
            $age_group = "Under $age_limit1";
        } elseif ($age_condition === 'Over') {
            $age_group = "Over $age_limit1";
        } elseif ($age_condition === 'Between') {
            $age_group = "Between $age_limit1 - $age_limit2";
        } elseif ($age_condition === 'Open') {
            $age_group = "Open";
        } else {
            $age_group = "";
        }

        // Validate additional age conditions.
        if ($age_condition === 'Under' && $age_limit1 >= 20) {
            return redirect()->back()->withInput()->withErrors("For 'Under' categories, age limit must be less than 20.");
        }
        if ($age_condition === 'Over' && $age_limit1 < 35) {
            return redirect()->back()->withInput()->withErrors("For 'Over' categories, age must be 35+.");
        }
        if ($age_condition === 'Between' && (!$age_limit2 || $age_limit1 >= $age_limit2)) {
            return redirect()->back()->withInput()->withErrors("For 'Between' categories, specify a valid age range (e.g., 'Between 20 - 35').");
        }

        // Create the category.
        $category = new Category();
        $category->name = $validated['name'];
        $category->age_group = $age_group;
        $category->sex = $validated['sex'];
        $category->created_by = Auth::id();
        $category->save();

        return redirect()->route('categories.index')->with('message', 'Category added successfully!');
    }

    /**
     * Show the form for editing an existing category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        // Extract age condition and limits from the stored age_group.
        $age_condition = '';
        $age_limit1 = null;
        $age_limit2 = null;
        if (preg_match('/^(Under|Over|Between)\s(\d+)(?:\s?-\s?(\d+))?/', $category->age_group, $matches)) {
            $age_condition = $matches[1];
            $age_limit1 = $matches[2];
            if (isset($matches[3])) {
                $age_limit2 = $matches[3];
            }
        } elseif ($category->age_group === 'Open') {
            $age_condition = 'Open';
        }

        return view('categories.edit', compact('category', 'age_condition', 'age_limit1', 'age_limit2'));
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'age_condition' => 'required|in:Under,Over,Between,Open',
            'age_limit1'    => 'nullable|integer',
            'age_limit2'    => 'nullable|integer',
            'sex'           => 'required|in:M,F,Mixed',
        ]);

        $age_condition = $validated['age_condition'];
        $age_limit1 = $validated['age_limit1'];
        $age_limit2 = $validated['age_limit2'];

        if ($age_condition === 'Under') {
            $age_group = "Under $age_limit1";
        } elseif ($age_condition === 'Over') {
            $age_group = "Over $age_limit1";
        } elseif ($age_condition === 'Between') {
            $age_group = "Between $age_limit1 - $age_limit2";
        } else {
            $age_group = "Open";
        }

        if ($age_condition === 'Under' && $age_limit1 >= 20) {
            return redirect()->back()->withInput()->withErrors("For 'Under' categories, age limit must be less than 20.");
        }
        if ($age_condition === 'Over' && $age_limit1 < 35) {
            return redirect()->back()->withInput()->withErrors("For 'Over' categories, age must be 35+.");
        }
        if ($age_condition === 'Between' && (!$age_limit2 || $age_limit1 >= $age_limit2)) {
            return redirect()->back()->withInput()->withErrors("For 'Between' categories, specify a valid age range (e.g., 'Between 20 - 35').");
        }

        $category = Category::findOrFail($id);
        $category->name = $validated['name'];
        $category->age_group = $age_group;
        $category->sex = $validated['sex'];
        $category->save();

        return redirect()->route('categories.index')->with('message', 'Category updated successfully!');
    }

    /**
     * Delete a category.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('message', 'Category deleted successfully!');
    }
}
