@extends('layouts.app')

@section('content')
    @include('layouts.headers.guest')
    <main>
        <section class="section section-shaped section-lg">

            <div class="container pt-lg-md" style="padding-top: 4rem!important">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <div class="card bg-secondary shadow border-0">
                            <div class="card-header bg-transparent text-center">
                                <h2>Sign up</h2>
                            </div>
                            <div class="card-body px-lg-5 py-lg-5">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-group  @error('name') has-danger @enderror mb-3">
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Name') }}" type="text" name="name" value="{{ old('name') }}" required autofocus>
                                        </div>
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" style="display: block;margin:10px 0px 10px 0px;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('E-Mail Address') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                        </div>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" style="display: block;margin:10px 0px 10px 0px;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            </div>
                                            <input  class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" type="password" name="password" value="{{ old('password') }}" required autocomplete="new-password">
                                        </div>
                                       
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" style="display: block;margin:10px 0px 10px 0px;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            </div>
                                            <input  class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" placeholder="{{ __('Password Confirmation') }}" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary my-4" style="background-color: #04703e">{{ __('Sign up') }}</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div class="row mt-3">
                            @if (Route::has('login'))
                                <div class="col-6">
                                    <a href="{{ route('login') }}" class="text-dark">
                                        <small>{{ __('Back to login') }}</small>
                                    </a>
                                </div>
                            @endif   

                        </div>
                       
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
