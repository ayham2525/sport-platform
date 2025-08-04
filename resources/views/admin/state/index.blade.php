@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')
@section('content')
@section('states')
@section('page_title')
   <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-map mr-1"></i> {{ __('state.index_title') }}
    </h5>
@endsection
@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-dashboard mr-1"></i> {{ __('state.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.states.index') }}" class="text-muted">
            <i class="la la-map mr-1"></i> {{ __('state.index_title') }}
        </a>
    </li>
</ul>
@endsection
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">

            @if (session('success'))
                <div class="alert alert-custom alert-success alert-dismissible fade show mb-5" role="alert">
                    <div class="alert-icon"><i class="la la-check-circle"></i></div>
                    <div class="alert-text">{{ __('state.' . session('success')) }}</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="la la-times"></i></span>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-custom alert-danger alert-dismissible fade show mb-5" role="alert">
                    <div class="alert-icon"><i class="la la-exclamation-circle"></i></div>
                    <div class="alert-text">{{ __('state.' . session('error')) }}</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="la la-times"></i></span>
                        </button>
                    </div>
                </div>
            @endif

            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">
                        {{ __('state.index_title') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('state.index_subtitle') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                 @if (PermissionHelper::hasPermission('create', App\Models\State::MODEL_NAME))
                    <a href="{{ route('admin.states.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus mr-1"></i> {{ __('state.create') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <table class="table table-separate table-head-custom table-checkable" id="states-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('state.name') }}</th>
                            <th>{{ __('state.country') }}</th>
                            <th>{{ __('state.created_at') }}</th>
                            <th>{{ __('state.status') }}</th>
                            <th>{{ __('state.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($states as $index => $state)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $state->name }}</td>
                            <td>{{ $state->country->name ?? '-' }}</td>
                            <td>{{ $state->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if ($state->is_active)
                                    <span class="badge badge-success">{{ __('state.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('state.inactive') }}</span>
                                @endif
                            </td>
                            <td nowrap>
                                {{-- <a href="{{ route('admin.states.show', $state->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.view') }}">
                                    <i class="la la-eye"></i>
                                </a> --}}
                                 @if (PermissionHelper::hasPermission('update', App\Models\State::MODEL_NAME))
                                <a href="{{ route('admin.states.edit', $state->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.edit') }}">
                                    <i class="la la-edit"></i>
                                </a>
                                @endif
                                 @if (PermissionHelper::hasPermission('delete', App\Models\State::MODEL_NAME))
                                <form action="{{ route('admin.states.destroy', $state->id) }}" method="POST" class="delete-form d-inline-block">
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
            </div>

        </div>
    </div>
</div>
@endsection



<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#states-table')) {
        $('#states-table').DataTable().clear().destroy();
    }

    $('#states-table').DataTable({
        paging: true,
        searching: true,
        responsive: true,
        autoWidth: false,
        language: {
            searchPlaceholder: "Search...",
            paginate: {
                previous: "<i class='la la-angle-left'></i>",
                next: "<i class='la la-angle-right'></i>"
            }
        }
    });

    $('.delete-button').click(function (e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the state permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f64e60',
            cancelButtonColor: '#c4c4c4',
            confirmButtonText: 'Yes, delete it!',
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


