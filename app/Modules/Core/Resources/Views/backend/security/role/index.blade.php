@extends('core::layouts.master')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">
        {{ __('Roles') }}
    </h1>

    <p class="mb-4">
        {{ __('Display a listing of the role.') }}
    </p>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('Index') }}
            </h6>

            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                    <div class="dropdown-header">
                        {{ __('Actions:') }}
                    </div>

                    <a class="dropdown-item" href="{{ route('backend.security.role.create') }}">
                        {{ __('Create New Role') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" id="roles-table">
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

                            <th>
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">#</h5>

                    <button class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{ __('Are you sure you want to delete this role?') }}
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">
                        {{ __('Cancel') }}
                    </button>

                    <a class="btn btn-primary" href="#"
                        onclick="event.preventDefault();
                            document.getElementById('delete-form').submit();">
                        {{ __('Delete') }}
                    </a>

                    <form id="delete-form" method="POST" style="display: none;">
                        @method('DELETE')

                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('backend.security.role.index.data') }}',
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'description'},
                    {data: 'actions', orderable: false, searchable: false},
                ],
            });
        });

        function deleteRole(roleId, action) {
            $('#delete-form').attr('action', action);

            $('#deleteModal .modal-title').text('{{ __('ID: ') }}' + roleId);

            $('#deleteModal').modal();
        }
    </script>
@endpush
