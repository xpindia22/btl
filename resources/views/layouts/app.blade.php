<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel BTL Project') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- 1) Load jQuery BEFORE Vite (only if you actually need jQuery) -->
    <!-- If your child views rely on jQuery, be sure to load it here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- 2) Your main app scripts & styles (compiled via Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Optionally, your own custom CSS (if any) -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Include the Header -->
    @include('layouts.header')
    @yield('styles')

    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- 3) Logout Link Script -->
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

    <!-- 4) Your additional scripts can go here (e.g. session.js) -->
    <script src="{{ asset('js/session.js') }}"></script>
    @include('partials.footer')
   
    <script>
  function verifyFuter() {
    const futer = document.getElementById('kopeerightFuter');
    if (!futer || !futer.innerText.includes('Robert James')) {
      alert('Required Copyright Footer is missing. The software will not run.');
      // Optionally, clear the page content or redirect:
      document.body.innerHTML = '<h1>Error: Required footer missing. Application halted.</h1>';
      // Throw the error to stop further script execution.
      throw new Error('Required Copyright Footer is missing.');
    }
  }
  document.addEventListener('DOMContentLoaded', verifyFuter);
</script>

</body>
</html>
