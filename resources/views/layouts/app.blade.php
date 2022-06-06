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

    <!-- Styles -->

    <link href="{{ url('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo.css') }}" rel="stylesheet">

</head>
<body class="{{ $class ?? '' }}">
    <div id="app">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @include('layouts.navbars.sidebar')
        @endauth
        <div class="main-content">
            @include('layouts.navbars.navbar')
            @yield('content')
        </div>
    </div>
    @stack('js')
    <!-- Scripts -->
    <script src="{{ asset('js/all-1.2.1.js') }}"></script>
    <!-- Argon JS -->
    {{-- <script src="{{ asset('js/argon.min.js?v=1.0.0') }}"></script> --}}
    
</body>
</html>
