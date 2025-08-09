@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('attendance.title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('attendance.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('attendance.title') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">{{ __('attendance.title') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('attendance.management') }}</span>
                    </h3>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        @if (PermissionHelper::hasPermission('create', App\Models\Attendance::MODEL_NAME))
                            <a href="{{ route('admin.attendance.create') }}" class="btn btn-success">
                                <i class="la la-plus"></i> {{ __('attendance.create_attendance') }}
                            </a>
                        @endif
                    </div>
                    <div>
                        @if (PermissionHelper::hasPermission('export', App\Models\Attendance::MODEL_NAME))
                            @php
                                $startDate = request('start_date') ?? \Carbon\Carbon::now()->startOfMonth()->toDateString();
                                $endDate = request('end_date') ?? \Carbon\Carbon::now()->endOfMonth()->toDateString();
                            @endphp
                            <form action="{{ route('admin.attendance.export') }}" method="GET" class="d-inline">
                                <input type="hidden" name="start_date" value="{{ $startDate }}">
                                <input type="hidden" name="end_date" value="{{ $endDate }}">
                                <input type="hidden" name="role" value="{{ request('role') }}">
                                <button type="submit" class="btn btn-info">
                                    <i class="la la-file-excel"></i> {{ __('attendance.export_excel') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- 🔍 Filters --}}
                <form method="GET" action="{{ route('admin.attendance.index') }}" class="form-inline mb-4">
                    <div class="form-group mr-2">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate) }}">
                    </div>
                    <div class="form-group mr-2">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate) }}">
                    </div>
                    <div class="form-group mr-2">
                        <select name="role" class="form-control">
                            <option value="">{{ __('attendance.select_role') }}</option>
                            <option value="full_admin" {{ request('role') == 'full_admin' ? 'selected' : '' }}>{{ __('roles.full_admin') }}</option>
                            <option value="system_admin" {{ request('role') == 'system_admin' ? 'selected' : '' }}>{{ __('roles.system_admin') }}</option>
                            <option value="branch_admin" {{ request('role') == 'branch_admin' ? 'selected' : '' }}>{{ __('roles.branch_admin') }}</option>
                            <option value="academy_admin" {{ request('role') == 'academy_admin' ? 'selected' : '' }}>{{ __('roles.academy_admin') }}</option>
                            <option value="coach" {{ request('role') == 'coach' ? 'selected' : '' }}>{{ __('roles.coach') }}</option>
                            <option value="player" {{ request('role') == 'player' ? 'selected' : '' }}>{{ __('roles.player') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary ml-2 mr-2">
    <i class="la la-filter"></i> {{ __('attendance.filter') }}
</button>

<a class="btn btn-secondary" href="{{ route('admin.attendance.index') }}">
    <i class="la la-times"></i> {{ __('attendance.clear') }}
</a>

                 </form>


                {{-- 📋 Attendance Table --}}
                <table class="table table-separate table-head-custom table-checkable" id="attendance-table">
                    <thead>
                        <tr>
    <th><i class="la la-hashtag"></i></th>
    <th><i class="la la-user"></i> {{ __('attendance.user') }}</th>
    <th><i class="la la-id-badge"></i> {{ __('attendance.role') }}</th>
    <th><i class="la la-building"></i> {{ __('attendance.branch') }}</th>
    <th><i class="la la-clock"></i> {{ __('attendance.scanned_at') }}</th>
    <th><i class="la la-cog"></i> {{ __('attendance.actions') }}</th>
</tr>

                    </thead>
                    <tbody>
                        @foreach ($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $attendance->user->name ?? '-' }}</td>
                            <td>{{ __('roles.' . ($attendance->user->role ?? '-')) }}</td>
                            <td>{{ $attendance->branch->name_ar ?? $attendance->branch->name ?? '-' }}</td>
                            <td>{{ $attendance->scanned_at }}</td>
                            <td nowrap>
                                @if (PermissionHelper::hasPermission('update', App\Models\Attendance::MODEL_NAME))
                                    <a href="{{ route('admin.attendance.edit', $attendance->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('attendance.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                @endif
                                @if (PermissionHelper::hasPermission('delete', App\Models\Attendance::MODEL_NAME))
                                    <form action="{{ route('admin.attendance.destroy', $attendance->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('attendance.delete') }}">
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
                    {{ $attendances->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 🧾 JS Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#attendance-table').DataTable({
            responsive: true,
            autoWidth: false,
            ordering: false,
            language: {
                searchPlaceholder: "{{ __('Search...') }}",
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
                title: '{{ __("attendance.delete_confirm_title") }}',
                text: '{{ __("attendance.delete_confirm_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f64e60',
                cancelButtonColor: '#c4c4c4',
                confirmButtonText: '{{ __("attendance.delete_confirm_yes") }}',
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
