@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-university mr-2"></i> {{ __('academy.title') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="la la-home mr-1"></i> {{ __('academy.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.academies.index') }}" class="text-muted">
                <i class="la la-university mr-1"></i> {{ __('academy.title') }}
            </a>
        </li>
    </ul>
@endsection

@section('content')
 <div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">
                        {{ __('academy.title') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('academy.subtitle') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\Academy::MODEL_NAME))
                        <a href="{{ route('admin.academies.create') }}" class="btn btn-primary font-weight-bolder">
                            <i class="la la-plus mr-1"></i> {{ __('academy.actions.create') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                {{-- Success & Error messages --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="la la-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="la la-exclamation-circle mr-1"></i> {{ __('academy.errors.general') }}
                        <ul class="mt-2 mb-0 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="GET" class="mb-5">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-4">
                            <select name="branch_id" class="form-control select2">
                                <option value="">{{ __('academy.filters.branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('academy.filters.search') }}" value="{{ request('search') }}">
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="la la-filter mr-1"></i> {{ __('academy.actions.filter') }}
                            </button>
                        </div>
                    </div>
                </form>

                <table class="table table-separate table-head-custom table-checkable" id="academies-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('academy.fields.name') }}</th>
                            <th>{{ __('academy.fields.branch') }}</th>
                            <th>{{ __('academy.fields.email') }}</th>
                            <th>{{ __('academy.fields.phone') }}</th>
                            <th>{{ __('academy.fields.status') }}</th>
                            <th>{{ __('academy.fields.created_at') }}</th>
                            <th>{{ __('academy.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($academies as $index => $academy)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $academy->name_en }}</td>
                            <td>{{ $academy->branch->name ?? '-' }}</td>
                            <td>{{ $academy->contact_email ?? '-' }}</td>
                            <td>{{ $academy->phone ?? '-' }}</td>
                            <td>
                                @if ($academy->is_active)
                                    <span class="badge badge-success">{{ __('academy.status.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('academy.status.inactive') }}</span>
                                @endif
                            </td>
                            <td>{{ $academy->created_at->format('Y-m-d') }}</td>
                            <td nowrap>
                                {{-- View players --}}
                            <a href="{{ route('admin.academies.players', $academy->id) }}"
                            class="btn btn-sm btn-clean btn-icon"
                            title="{{ __('academy.actions.view_players') }}">
                                <i class="la la-users"></i>
                            </a>
                                @if (PermissionHelper::hasPermission('update', App\Models\Academy::MODEL_NAME))
                                    <a href="{{ route('admin.academies.edit', $academy->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('academy.actions.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                @endif
                                @if (PermissionHelper::hasPermission('delete', App\Models\Academy::MODEL_NAME))
                                    <form action="{{ route('admin.academies.destroy', $academy->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('academy.actions.delete') }}">
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
                    {{ $academies->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#academies-table').DataTable({
        responsive: true,
        paging: false,
        searching: false,
        info: false
    });

    $('.select2').select2();

   $(document).on('click', '.delete-button', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: '{{ __("academy.delete_confirm.title") }}',
            text: '{{ __("academy.delete_confirm.text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f64e60',
            cancelButtonColor: '#c4c4c4',
            confirmButtonText: '{{ __("academy.delete_confirm.confirm") }}',
            cancelButtonText: '{{ __("academy.delete_confirm.cancel") }}',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>


