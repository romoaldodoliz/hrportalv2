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
                                <h2>{{ __('Reset password') }}</h2>
                            </div>
                            <div class="card-body px-lg-5 py-lg-5">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <form role="form" method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="form-group @error('email') has-danger @enderror mb-3">
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                            </div>
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" style="display: block;margin:10px 0px 10px 0px;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary my-4" style="background-color: #04703e">{{ __('Send Password Reset Link') }}</button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <a href="{{ route('login') }}" class="text-dark">
                                    <small>{{ __('Back to Login') }}</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
