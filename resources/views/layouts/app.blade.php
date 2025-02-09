<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

</head>
<body>
    <!-- Include the Header -->
    @include('layouts.header')

    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Logout Form (Hidden) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Logout Link (Triggers POST Request) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const logoutLink = document.getElementById("logout-link");
            if (logoutLink) {
                logoutLink.addEventListener("click", function(event) {
                    event.preventDefault();
                    document.getElementById("logout-form").submit();
                });
            }
        });
    </script>

    <!-- Include session.js -->
    <script src="{{ asset('js/session.js') }}"></script>
</body>
</html>
