@extends('core::layouts.master')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">
        {{ __('Account Settings') }}
    </h1>

    <p class="mb-4">
        {{ __('Show the form for editing the account settings.') }}
    </p>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('Account') }}
                    </h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('backend.settings.account') }}" method="POST">
                        @method('PATCH')

                        @csrf

                        <div class="form-group">
                            <label for="email">
                                {{ __('E-Mail Address') }}

                                -

                                @if (Auth::user()->hasVerifiedEmail())
                                    <span class="text-success">
                                        {{ __('Verified') }}
                                    </span>
                                @else
                                    <span class="text-warning">
                                        {{ __('Unverified') }}
                                    </span>
                                @endif
                            </label>

                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ Auth::user()->email }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">
                                {{ __('New Password') }}
                            </label>

                            <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">
                                {{ __('Confirm New Password') }}
                            </label>

                            <input class="form-control" id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password">
                        </div>

                        <button class="btn btn-secondary" type="reset">
                            {{ __('Reset') }}
                        </button>

                        <button class="btn btn-primary" type="submit">
                            {{ __('Update') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
