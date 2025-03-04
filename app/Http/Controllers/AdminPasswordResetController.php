<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPasswordResetController extends Controller
{
    public function index()
    {
        // Only admins should access this
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Fetch reset links from the database
        $passwordResets = DB::table('admin_password_resets')->orderBy('created_at', 'desc')->get();

        return view('admin.password-resets', compact('passwordResets'));
    }
}
