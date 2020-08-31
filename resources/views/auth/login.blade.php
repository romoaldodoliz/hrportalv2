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
    
    <link href="{{ asset('css/argon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo.css') }}" rel="stylesheet">

</head>
<body class="{{ $class ?? '' }}">
    <div id="app">
        <div class="main-content">
            @include('layouts.headers.guest')
            <main>
                <section class="section section-shaped section-lg">
                    {{-- <div class="separator separator-bottom separator-skew zindex-100">
                        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <polygon class="fill-primary" points="2560 0 2560 100 0 100"></polygon>
                        </svg>
                    </div> --}}
                    <div class="container" style="padding-top: 1rem!important" >
                        <div class="row justify-content-center">
                            <div class="col-lg-9 col-md-7">
                                <div class="alert alert-primary alert-with-icon" data-notify="container">
                                    <i class="fas fa-bullhorn"></i> Announcement
                                    <span data-notify="message">

                                        <ul style="padding-bottom:0px!important;margin-bottom:0px!important;">
                                            <li>
                                                To sign in, use your username and password in <strong style="color:green">My Portal</strong>
                                            </li>
                                            <li>
                                                Forgot your password? Just click <a href="http://10.96.4.70/password/reset" target="_blank"><strong style="color:red">here</strong> </a>  to reset.
                                            </li>
                                        </ul>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-7">
                                

                                <div class="card bg-white shadow border-0" >
                                    <div class="card-header bg-white text-center" style="border-bottom:2px solid #04703E;">
                                        <h2>Sign in your account</h2>
                                        
                                    </div>
                                    <div class="card-body px-lg-5 py-lg-5">
                                        <form role="form" method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                                <div class="input-group input-group-alternative">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                                </div>
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" style="display: block;margin:0px 0px 10px 0px;" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                                <div class="input-group input-group-alternative">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    </div>
                                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required>
                                                </div>
                                            
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" style="display: block;margin:0px 0px 10px 0px;" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                            <div class="custom-control custom-control-alternative custom-checkbox">
                                                <input class="custom-control-input" name="remember" id="customCheckLogin" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customCheckLogin">
                                                    <span class="text-dark">{{ __('Remember me') }}</span>
                                                </label>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary my-4" style="background-color: #04703e">{{ __('Sign in') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                </section>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/argon.min.js?v=1.0.0') }}"></script>  
</body>
</html>