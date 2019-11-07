@extends('core::layouts.auth')

@section('content')
    <div class="row justify-content-center">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
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
                                        {{ __('Reset Password') }}
                                    </h1>

                                    <p class="mb-4">
                                        {{ __("We get it, stuff happens. Just enter your email address below and we'll send you a link to reset your password!")}}
                                    </p>
                                </div>

                                <form action="{{ route('password.email') }}" class="user" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <input class="form-control form-control-user @error('email') is-invalid @enderror" name="email" placeholder="{{ __('E-Mail Address') }}" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button class="btn btn-primary btn-user btn-block" type="submit">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">
                                        {{ __('Remember your password?') }}
                                    </a>
                                </div>

                                @if (Route::has('register'))
                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">
                                            {{ __('Create an account!') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
