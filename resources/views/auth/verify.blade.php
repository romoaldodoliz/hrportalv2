@extends('layouts.app')

@section('content')


<div class="header container-list">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col-xl-12 col-lg-6">
                        <div class="card shadow shadow-lg--hover">
                            <div class="card-body">
                                <div class="d-flex px-3">
                                    <div>
                                        <div class="icon icon-shape bg-gradient-success rounded-circle text-white">
                                            <i class="fas fa-info"></i>
                                        </div>
                                    </div>
                                    <div class="pl-4">
                                        <h5 class="title text-success">{{ __('Verify Your Email Address') }}</h5>

                                        @if (session('resent'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <span class="alert-inner--text"> {{ __('A fresh verification link has been sent to your email address.') }}</span>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        @endif

                                        <p> {{ __('Before proceeding, please check your email for a verification link.') }}
                                        {{ __('If you did not receive the email. Just click ') }} <a href="{{ route('verification.resend') }}" class="text-success">{{ __('Resend Email') }}</a>.</p>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
