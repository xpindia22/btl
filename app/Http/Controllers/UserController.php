<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function data()
    {
        return response()->json([
            'message' => 'User data route is working!',
            'users' => [] // Example empty data, replace with actual user data if needed
        ]);
    }
}
