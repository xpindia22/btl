@php
    use Illuminate\Support\Facades\Auth;

    $logged_in_user = Auth::check() ? Auth::user()->username : 'Guest';
    $user_Role = Auth::check() ? strtolower(Auth::user()->role ?? 'guest') : 'guest'; // Ensuring case consistency
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
            <a href="{{ route('player.register') }}">Register Player</a>

            @if ($user_Role === 'admin')
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
                    @if (in_array($user_Role, ['admin', 'moderator', 'user'])) 
                        <a href="{{ route('matches.singles.create') }}">Add Singles</a>
                    @endif
                    <a href="{{ route('results.singles') }}">Singles Results</a>
                    @if (in_array($user_Role, ['admin', 'moderator', 'user'])) 
                        <!-- Linking to the index where singles matches can be managed -->
                        <a href="{{ route('matches.singles.index') }}">Edit Singles Matches</a>
                    @endif
                </div>
            </div>

            <!-- Dropdown: Boys Doubles -->
            <div class="dropdown">
                <a href="#">Boys Doubles</a>
                <div class="dropdown-content">
                    @if (in_array($user_Role, ['admin', 'moderator', 'user'])) 
                        <a href="{{ route('matches.doubles_boys.create') }}">Insert Boys Doubles</a>
                    @endif
                    <a href="{{ route('results.boys_doubles') }}">Result Boys Doubles</a>
                    @if (in_array($user_Role, ['admin', 'moderator', 'user'])) 
                        <!-- Assuming you have an index route for boys doubles; adjust if needed -->
                        <a href="{{ route('matches.doubles_boys.index') }}">Edit Boys Doubles</a>
                    @endif
                </div>
            </div>

            <!-- Logout Form (Hidden) -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <!-- Logout Button -->
            <a href="#" id="logout-link">Logout</a>
        </div>
    </div>

    <!-- Script to Handle Logout via POST -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("logout-link").addEventListener("click", function(event) {
                event.preventDefault();
                document.getElementById("logout-form").submit();
            });
        });
    </script>
</body>
</html>
