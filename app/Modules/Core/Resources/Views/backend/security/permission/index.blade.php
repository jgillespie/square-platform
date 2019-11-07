@extends('core::layouts.master')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">
        {{ __('Permissions') }}
    </h1>

    <p class="mb-4">
        {{ __('Display a listing of the permission.') }}
    </p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('Index') }}
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" id="permissions-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>
                                {{ __('ID') }}
                            </th>

                            <th>
                                {{ __('Name') }}
                            </th>

                            <th>
                                {{ __('Description') }}
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#permissions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('backend.security.permission.index.data') }}',
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'description'},
                ],
            });
        });
    </script>
@endpush
