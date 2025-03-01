<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
//login fxn before messign around with session time .
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);
    
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $request->session()->regenerate();
    
    //         return redirect()->intended('/dashboard')->with('success', 'Login successful!');
    //     }
    
    //     return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    // }
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'remember' => 'sometimes|boolean', // ✅ Fix validation issue
    ]);

    $remember = $request->has('remember'); // ✅ Properly retrieve remember value

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard')->with('success', 'Login successful!');
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
}

    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
