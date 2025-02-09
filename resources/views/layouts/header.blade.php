@php
    use Illuminate\Support\Facades\Auth;

    $logged_in_user = Auth::check() ? Auth::user()->username : 'Guest';
    $user_role = Auth::check() ? Auth::user()->role : 'guest';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badminton Tournament</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles1.css') }}">

    <style>
        /* Reset margins and padding for the body */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        /* Header styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f4f4f4;
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
        }

        /* Welcome message */
        .header .welcome {
            font-size: 14px;
            color: #333;
        }

        /* Links container */
        .header .links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        /* Individual links */
        .header .links a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        /* Dropdown styling */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            min-width: 220px;
            border-radius: 4px;
        }

        .dropdown-content a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 10px 16px;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Welcome Message -->
        <div class="welcome">
            <span>Welcome, {{ $logged_in_user }}</span>
        </div>
        <!-- Navigation Links -->
        <div class="links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('register') }}">Register Tournament Manager</a>
            <a href="{{ route('register_player') }}">Register Player</a>

            @if ($user_role === 'admin')
                <!-- Dropdown: Admin Zone -->
                <div class="dropdown">
                    <a href="#">Admin Zone</a>
                    <div class="dropdown-content">
                        <a href="{{ route('admin.edit_users') }}">Edit Tournament Manager</a>
                        <a href="{{ route('admin.edit_players') }}">Edit Player</a>
                        <a href="{{ route('matches.create') }}">Insert Match</a> 
                        <a href="{{ route('categories.create') }}">Insert Category</a> 
                        <a href="{{ route('admin.add_moderator') }}">Add Moderator</a>
                        <a href="{{ route('tournaments.create') }}">Insert Tournament</a>
                    </div>
                </div>
            @endif

            <!-- Dropdown: Singles Matches -->
            <div class="dropdown">
                <a href="#">Singles Matches</a>
                <div class="dropdown-content">
                    @if (in_array($user_role, ['admin', 'moderator', 'user'])) 
                        <a href="{{ route('matches.create_singles') }}">Add Singles</a>
                    @endif
                    <a href="{{ route('results.singles') }}">Singles Results</a>
                    @if (in_array($user_role, ['admin', 'moderator', 'user'])) 
                        <a href="{{ route('matches.edit_singles') }}">Edit Singles Matches</a>
                    @endif
                </div>
            </div>

            <!-- Dropdown: Boys Doubles -->
            <div class="dropdown">
                <a href="#">Boys Doubles</a>
                <div class="dropdown-content">
                    @if (in_array($user_role, ['admin', 'moderator', 'user'])) 
                        <a href="{{ route('matches.create_boys_doubles') }}">Insert Boys Doubles</a>
                    @endif
                    <a href="{{ route('results.boys_doubles') }}">Result Boys Doubles</a>
                    @if (in_array($user_role, ['admin', 'moderator', 'user'])) 
                        <a href="{{ route('matches.edit_boys_doubles') }}">Edit Boys Doubles</a>
                    @endif
                    @if ($user_role === 'admin') 
                        <a href="{{ route('matches.edit_all_doubles') }}">Edit All Doubles</a>
                    @endif
                </div>
            </div>

            <!-- Logout -->
            <a href="{{ route('logout') }}">Logout</a>
        </div>
    </div>
</body>
</html>
