@extends('core::layouts.master')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">
        {{ __('Account') }}
    </h1>

    <p class="mb-4">
        {{ __('Show the form for creating a new account.') }}
    </p>

    <form action="{{ route('backend.security.account.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{ __('Create') }}
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('is_enabled') is-invalid @enderror" id="is-enabled-1" name="is_enabled" type="radio" value="1"{{ old('is_enabled') === '0' ? '' : ' checked' }}>

                                <label class="form-check-label" for="is-enabled-1">
                                    {{ __('Enabled') }}
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('is_enabled') is-invalid @enderror" id="is-enabled-2" name="is_enabled" type="radio" value="0"{{ old('is_enabled') === '0' ? ' checked' : '' }}>

                                <label class="form-check-label" for="is-enabled-2">
                                    {{ __('Disabled') }}
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('is_backend') is-invalid @enderror" id="is-backend-1" name="is_backend" type="radio" value="1"{{ old('is_backend') === '0' ? '' : ' checked' }}>

                                <label class="form-check-label" for="is-backend-1">
                                    {{ __('Backend') }}
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('is_backend') is-invalid @enderror" id="is-backend-2" name="is_backend" type="radio" value="0"{{ old('is_backend') === '0' ? ' checked' : '' }}>

                                <label class="form-check-label" for="is-backend-2">
                                    {{ __('Frontend') }}
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="email">
                                {{ __('E-Mail Address') }}
                            </label>

                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

                            @error('email')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('email_verified') is-invalid @enderror" id="email-verified" name="email_verified" type="checkbox"{{ old('email_verified') === 'on' ? ' checked' : '' }}>

                                <label class="form-check-label" for="email-verified">
                                    {{ __('Verified e-mail address?') }}
                                </label>
                            </div>
                        </div>

                        <a class="btn btn-secondary" href="{{ route('backend.security.account.index') }}">
                            {{ __('Cancel') }}
                        </a>

                        <button class="btn btn-primary" type="submit">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{ __('Associated Roles') }}
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            {{ __('ID') }}
                                        </th>

                                        <th></th>

                                        <th>
                                            {{ __('Name') }}
                                        </th>

                                        <th>
                                            {{ __('Description') }}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>
                                                {{ $role->id }}
                                            </td>

                                            <td>
                                                <input name="roles[]" type="checkbox" value="{{ $role->id }}"{{ old('roles') && in_array($role->id, old('roles')) ? ' checked' : '' }}>
                                            </td>

                                            <td>
                                                {{ $role->name }}
                                            </td>

                                            <td>
                                                {{ $role->description }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
