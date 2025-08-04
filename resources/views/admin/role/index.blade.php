@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.roles') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.roles.index') }}" class="text-muted">{{ __('titles.roles') }}</a>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('columns.roles') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('titles.roles_management') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                 @if (PermissionHelper::hasPermission('create', App\Models\Role::MODEL_NAME))
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i> {{ __('titles.new_record') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- 🔍 Filters --}}
                <form method="GET" action="{{ route('admin.roles.index') }}" class="mb-5">
                    <div class="form-row align-items-end">
                        <div class="col-md-4 mb-2">
                            <label>{{ __('columns.name') }}</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('columns.name') }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>{{ __('columns.system') }}</label>
                            <select name="system_id" class="form-control">
                                <option value="">{{ __('columns.all') }}</option>
                                @foreach($systems as $system)
                                    <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>
                                        {{ $system->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter"></i> {{ __('actions.filter') }}
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-separate table-head-custom table-checkable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('columns.name') }}</th>
                                <th>{{ __('columns.slug') }}</th>
                                <th>{{ __('columns.description') }}</th>
                                <th>{{ __('columns.system') }}</th>
                                <th>{{ __('columns.created_at') }}</th>
                                <th>{{ __('columns.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->slug }}</td>
                                <td>{{ $role->description ?? '-' }}</td>
                                <td>{{ $role->system->name ?? __('columns.global') }}</td>
                                <td>{{ $role->created_at->format('Y-m-d') }}</td>
                                <td nowrap>
                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.view') }}">
                                        <i class="la la-eye"></i>
                                    </a>
                                    @if (PermissionHelper::hasPermission('update', App\Models\Role::MODEL_NAME))
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.edit') }}">
                                            <i class="la la-edit"></i>
                                        </a>
                                    @endif
                                    @if (PermissionHelper::hasPermission('delete', App\Models\Role::MODEL_NAME))
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('actions.delete') }}">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('messages.no_data') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
