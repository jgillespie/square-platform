@extends('core::layouts.auth')

@section('content')
    <div class="row justify-content-center">
        @if (session('resent'))
            <div class="alert alert-success">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-auth-image"></div>

                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">
                                        {{ __('Verify Your Email Address') }}
                                    </h1>

                                    <p class="mb-4">
                                        {{ __('Before proceeding, please check your email for a verification link.') }}
                                    </p>
                                </div>

                                <hr>

                                @if (Auth::user()->is_backend)
                                    <div class="text-center">
                                        <a class="small" href="{{ route('backend.settings.account') }}">
                                            {{ __('Wrong e-mail address?') }}
                                        </a>
                                    </div>
                                @else
                                    @if (config('core.frontend_routes_prefix') !== false)
                                        <div class="text-center">
                                            <a class="small" href="{{ route('frontend.settings.account') }}">
                                                {{ __('Wrong e-mail address?') }}
                                            </a>
                                        </div>
                                    @endif
                                @endif

                                <div class="text-center">
                                    {{ __('If you did not receive the email,') }}

                                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 m-0 align-baseline">{{ __('click here') }}</button>
                                    </form>

                                    {{ __('to request another.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
