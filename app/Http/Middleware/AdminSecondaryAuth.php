<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSecondaryAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure the user is logged in using Laravel's auth system
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get the username from the logged-in user
        $username = auth()->user()->username;

        // Define your secondary admin credentials
        $adminAuth = [
            'admin' => 'xxxx',
            'xxx'   => 'xxxx',
        ];

        // Check if the logged in user is in the secondary admin list
        if (!array_key_exists($username, $adminAuth)) {
            abort(403, "Access denied: You do not have the required permissions.");
        }

        // Check if secondary authentication has already been performed for this page.
        // You can use the session to store pages that are already authenticated.
        $currentPage = $request->path();
        $authenticatedPages = session('double_authenticated_pages', []);

        if (!in_array($currentPage, $authenticatedPages)) {
            // If the form has been submitted, verify the provided secondary password
            if ($request->isMethod('post') && $request->has('auth_password')) {
                $provided = $request->input('auth_password');
                if ($provided === $adminAuth[$username]) {
                    // Mark this page as authenticated
                    $authenticatedPages[] = $currentPage;
                    session(['double_authenticated_pages' => $authenticatedPages]);
                    return $next($request);
                } else {
                    return response("Invalid secondary password.", 403);
                }
            }

            // Otherwise, show a simple secondary authentication form.
            return response()->view('auth.secondary', ['currentPage' => $currentPage]);
        }

        return $next($request);
    }
}
