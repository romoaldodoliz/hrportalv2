<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HR PORTAL</title>

    <!-- Icons -->
    <link href="{{ asset('/img/lfuggoc_icon.png') }}" rel="icon" type="image/png">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Styles -->
    
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo.css') }}" rel="stylesheet">

</head>
<body class="{{ $class ?? '' }}">
    <div id="app">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth
        
        <div class="main-content">
            @include('layouts.navbars.user_profile_navbar')
            @yield('content')
        </div>
    </div>
    @stack('js')
    <!-- Scripts -->
    <script src="{{ asset('js/all-1.2.0.js') }}" defer></script>
    <!-- Argon JS -->
    {{-- <script src="{{ asset('js/argon.min.js?v=1.0.0') }}"></script> --}}
    
</body>
</html>
