@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-dumbbell mr-1"></i> {{ __('sport.titles.sports_list') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home  mr-1"></i> {{ __('sport.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('sport.titles.sports') }}</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-dumbbell mr-2"></i> {{ __('sport.titles.sports') }}
            </h3>
             @if (PermissionHelper::hasPermission('create', App\Models\Sport::MODEL_NAME))
                <a href="{{ route('admin.sports.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i> {{ __('sport.actions.add') }}
                </a>
            @endif
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="sports-table" class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-font  mr-1"></i> {{ __('sport.fields.name_en') }}</th>
                            <th><i class="fas fa-globe  mr-1"></i> {{ __('sport.fields.name_ar') }}</th>
                            <th><i class="fas fa-align-left  mr-1"></i> {{ __('sport.fields.description') }}</th>
                            <th><i class="fas fa-icons t mr-1"></i> {{ __('sport.fields.icon') }}</th>
                            <th><i class="fas fa-toggle-on  mr-1"></i> {{ __('sport.fields.status') }}</th>
                            <th class="text-center"><i class="fas fa-cogs  mr-1"></i> {{ __('sport.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sports as $index => $sport)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sport->name_en }}</td>
                                <td>{{ $sport->name_ar }}</td>
                                <td>{{ $sport->description }}</td>
                                <td><i class="{{ $sport->icon }}"></i></td>
                                <td>
                                    <span class="badge badge-{{ $sport->is_active ? 'success' : 'secondary' }}">
                                        {{ $sport->is_active ? __('sport.status.active') : __('sport.status.inactive') }}
                                    </span>
                                </td>
                                <td nowrap class="text-center">
                                    @if (PermissionHelper::hasPermission('update', App\Models\Sport::MODEL_NAME))
                                    <a href="{{ route('admin.sports.edit', $sport->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('sport.actions.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    @endif
                                    @if (PermissionHelper::hasPermission('delete', App\Models\Sport::MODEL_NAME))
                                    <form action="{{ route('admin.sports.destroy', $sport->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('sport.actions.delete') }}">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">{{ __('sport.messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
               {{ $sports->links('pagination::bootstrap-4') }}

            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#sports-table')) {
            $('#sports-table').DataTable().clear().destroy();
        }

        $('#sports-table').DataTable({
            paging: true,
            searching: true,
            responsive: true,
            autoWidth: false,
            language: {
                searchPlaceholder: "{{ __('sport.actions.search') }}...",
                paginate: {
                    previous: "<i class='la la-angle-left'></i>",
                    next: "<i class='la la-angle-right'></i>"
                }
            }
        });

        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: '{{ __("sport.messages.confirm_delete_title") }}',
                text: '{{ __("sport.messages.confirm_delete_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f64e60',
                cancelButtonColor: '#c4c4c4',
                confirmButtonText: '{{ __("sport.messages.yes_delete") }}',
                cancelButtonText: '{{ __("sport.actions.cancel") }}',
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

@endsection


