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
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f4f4f4;
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
        }
        .header .welcome {
            font-size: 14px;
            color: #333;
        }
        .header .links {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .header .links a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
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
                        <a href="{{ url('users/create') }}">Create User</a>
                        <a href="{{ url('tournaments/manage') }}">Manage Tournaments & Add Moderator</a>
                        <a href="{{ route('admin.edit_players') }}">Edit Player</a>
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
                    <a href="{{ route('matches.singles.index') }}">Singles Results</a>
                    @if (in_array($user_Role, ['admin', 'moderator', 'user']))
                        <!-- Ensure a match ID is available for editing -->
                        @if(isset($latestMatch))
                            <a href="{{ route('matches.singles.edit', ['match' => $latestMatch->id]) }}">Manage Singles</a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Dropdown: Doubles - BD-GD-XD -->
            <div class="dropdown">
                <a href="#">Doubles & Mixed Doubles</a>
                <div class="dropdown-content">
                    @if (in_array($user_Role, ['admin', 'moderator', 'user'])) 
                        <a href="{{ route('matches.doubles.create') }}">Create Boys Doubles</a>
                    @endif
                    <a href="{{ route('matches.doubles.index') }}">Result Boys Doubles</a>
                    @if (in_array($user_Role, ['admin', 'moderator', 'user'])) 
                        <!-- Ensure a match ID is available for editing -->
                        @if(isset($latestDoublesMatch))
                            <a href="{{ route('matches.doubles.edit', ['match' => $latestDoublesMatch->id]) }}">Edit Doubles</a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Match Results Dropdown -->
            <div class="dropdown">
                <a href="#">Match Results</a>
                <div class="dropdown-content">
                    <a href="{{ route('matches.singles.index') }}">Singles Results</a>
                    @if (in_array($user_Role, ['admin', 'moderator', 'user']))
                        <a href="{{ route('matches.doubles.index') }}">Doubles Results</a>
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
