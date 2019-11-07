@extends('core::layouts.master')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">
        {{ __('Roles') }}
    </h1>

    <p class="mb-4">
        {{ __('Show the form for editing the specified role.') }}
    </p>

    <form action="{{ route('backend.security.role.update', ['role' => $role->id]) }}" method="POST">
        @method('PATCH')

        @csrf

        <div class="row">
            <div class="col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{ __('Edit') }}
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">
                                {{ __('Name') }}
                            </label>

                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ $role->name }}" required>

                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">
                                {{ __('Description') }}
                            </label>

                            <input class="form-control @error('description') is-invalid @enderror" id="description" name="description" type="text" value="{{ $role->description }}" required>

                            @error('description')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <a class="btn btn-secondary" href="{{ route('backend.security.role.index') }}">
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
                            {{ __('Associated Permissions') }}
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
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td>
                                                {{ $permission->id }}
                                            </td>

                                            <td>
                                                <input name="permissions[]" type="checkbox" value="{{ $permission->id }}"{{ is_null($role->permissions()->find($permission->id)) ? '' : ' checked' }}>
                                            </td>

                                            <td>
                                                {{ $permission->name }}
                                            </td>

                                            <td>
                                                {{ $permission->description }}
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
