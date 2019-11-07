@extends('core::layouts.auth')

@section('content')
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-auth-image"></div>

                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">
                                {{ __('Register') }}
                            </h1>
                        </div>

                        <form action="{{ route('register') }}" class="user" method="POST">
                            @csrf

                            <div class="form-group">
                                <input class="form-control form-control-user @error('email') is-invalid @enderror" name="email" placeholder="{{ __('E-Mail Address') }}" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" type="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <input class="form-control form-control-user" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" type="password" required autocomplete="new-password">
                                </div>
                            </div>

                            <button class="btn btn-primary btn-user btn-block" type="submit">
                                {{ __('Register') }}
                            </button>
                        </form>

                        <hr>

                        <div class="text-center">
                            <a class="small" href="{{ route('login') }}">
                                {{ __('Already have an account?') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
