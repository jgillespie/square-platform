@extends('core::layouts.auth')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-auth-image"></div>

                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">
                                        {{ __('Reset Password') }}
                                    </h1>
                                </div>

                                <form action="{{ route('password.update') }}" class="user" method="POST">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group">
                                        <input class="form-control form-control-user @error('email') is-invalid @enderror" name="email" placeholder="{{ __('E-Mail Address') }}" type="email" value="{{ $email ?? old('email') }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" type="password" required autocomplete="new-password" autofocus>

                                        @error('password')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input class="form-control form-control-user" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" type="password" required autocomplete="new-password">
                                    </div>

                                    <button class="btn btn-primary btn-user btn-block" type="submit">
                                        {{ __('Reset Password') }}
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
