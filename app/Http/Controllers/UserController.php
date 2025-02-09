<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.users_index', compact('users'));
    }

    public function create()
    {
        return view('users.users_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mobile_no' => 'nullable|digits:10',
            'role' => 'in:visitor,user'
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'role' => $request->role ?? 'visitor',
        ]);

        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $user = User::findOrFail($id);

        // Ensure the view file exists in the correct path
        return view('users.users_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile_no' => 'nullable|digits:10',
            'role' => 'in:admin,user,visitor'
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id) // ✅ Fix: Removed $request parameter
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
