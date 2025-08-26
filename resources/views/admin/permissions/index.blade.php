@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.permissions') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('titles.permissions') }}</span>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('titles.permissions') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('titles.permissions_management') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\Permission::MODEL_NAME))
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary font-weight-bolder">
                            <i class="la la-plus"></i> {{ __('titles.new_record') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Search --}}
                <form method="GET" action="{{ route('admin.permissions.index') }}" class="mb-5">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('columns.action') }}" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> {{ __('actions.search') }}
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Permissions Table --}}
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                             <th>{{ __('columns.system') }}</th>
                            <th>{{ __('columns.role') }}</th>
                              <th>{{ __('columns.model') }}</th>
                            <th>{{ __('columns.action') }}</th>
                            <th>{{ __('columns.created_at') }}</th>
                            <th>{{ __('columns.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paginated as $index => $permission)
                            <tr>
                                <td>{{ $index + $paginated->firstItem() }}</td>
                                <td>{{ $permission->role->system->name ?? 'N/A' }}</td>
                                <td>{{ $permission->role->name ?? 'N/A' }}</td>
                                <td>{{ $permission->model->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($permission->action) }}</td>
                                <td>{{ $permission->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if (PermissionHelper::hasPermission('update', App\Models\Permission::MODEL_NAME))
                                        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.edit') }}">
                                            <i class="la la-edit"></i>
                                        </a>
                                    @endif

                                    @if (PermissionHelper::hasPermission('delete', App\Models\Permission::MODEL_NAME))
                                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('actions.delete') }}">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $paginated->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation --}}
<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: '{{ __("messages.confirm_delete_title") }}',
                text: '{{ __("messages.confirm_delete_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __("messages.yes_delete") }}',
                cancelButtonText: '{{ __("messages.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection
