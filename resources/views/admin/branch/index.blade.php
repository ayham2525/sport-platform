@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('branch.title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('branch.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.branches.index') }}" class="text-muted">
            <i class="la la-building mr-1"></i> {{ __('branch.title') }}
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
                    <h3 class="card-label">{{ __('branch.title') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('branch.management') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\Branch::MODEL_NAME))
                    <a href="{{ route('admin.branches.create') }}" class="btn btn-primary font-weight-bolder">
                        <span class="svg-icon svg-icon-md"><i class="la la-plus"></i></span>
                        {{ __('branch.new_branch') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <form method="GET" class="mb-5">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-2 px-1 mb-2">
                            <select name="country_id" class="form-control" id="country_id">
                                <option value="">{{ __('branch.select_country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-2 px-1 mb-2">
                            <select name="state_id" class="form-control" id="state_id">
                                <option value="">{{ __('branch.select_state') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-2 px-1 mb-2">
                            <select name="city_id" id="city_id" class="form-control">
                                <option value="">{{ __('branch.select_city') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-2 px-1 mb-2">
                            <select name="system_id" id="system_id" class="form-control">
                                <option value="">{{ __('branch.select_system') }}</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>{{ $system->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-2 px-1 mb-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter" aria-hidden="true"></i> {{ __('branch.filter') }}
                            </button>
                        </div>
                    </div>
                </form>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-separate table-head-custom table-checkable" id="branches-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('branch.branch_name') }}</th>
                            <th>{{ __('branch.city') }}</th>
                            <th>{{ __('branch.state') }}</th>
                            <th>{{ __('branch.country') }}</th>
                            <th>{{ __('branch.system') }}</th>
                            <th>{{ __('branch.status') }}</th>
                            <th>{{ __('branch.created_at') }}</th>
                            <th>{{ __('branch.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branches as $index => $branch)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->city->name ?? '-' }}</td>
                                <td>{{ $branch->city->state->name ?? '-' }}</td>
                                <td>{{ $branch->city->state->country->name ?? '-' }}</td>
                                <td>{{ $branch->system->name ?? '-' }}</td>
                                <td>
                                    @if ($branch->is_active)
                                        <span class="badge badge-success">{{ __('branch.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('branch.inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $branch->created_at->format('Y-m-d') }}</td>
                                <td nowrap>
                                      <a href="{{ route('admin.branches.players', $branch->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('branch.view_players') }}">
                                        <i class="la la-users"></i>
                                    </a>
                                    <a href="{{ route('admin.branches.items', $branch->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('branch.view_items') }}">
                                      <i class="la la-box"></i>
                                    </a>
                                    @if (PermissionHelper::hasPermission('update', App\Models\Branch::MODEL_NAME))
                                    <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('branch.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    @endif
                                    @if (PermissionHelper::hasPermission('delete', App\Models\Branch::MODEL_NAME))
                                    <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('branch.delete') }}">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('#branches-table').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            searchPlaceholder: "{{ __('Search...') }}",
            paginate: {
                previous: "<i class='la la-angle-left'></i>",
                next: "<i class='la la-angle-right'></i>"
            }
        }
    });

    $(document).on('click', '.delete-button', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: '{{ __("branch.delete_confirm_title") }}',
            text: '{{ __("branch.delete_confirm_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f64e60',
            cancelButtonColor: '#c4c4c4',
            confirmButtonText: '{{ __("branch.delete_confirm_yes") }}',
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

    $('#country_id, #state_id, #city_id, #system_id').select2();

    $('#country_id').change(function () {
        let countryID = $(this).val();
        $.ajax({
            url: '{{ route('admin.getStatesByCountry') }}',
            type: 'GET',
            data: { country_id: countryID },
            success: function (data) {
                let options = '<option value="">{{ __("branch.select_state") }}</option>';
                $.each(data, function (key, state) {
                    options += `<option value="${state.id}">${state.name}</option>`;
                });
                $('#state_id').html(options).trigger('change');
            }
        });
    });

    $('#state_id').change(function () {
        let stateID = $(this).val();
        $.ajax({
            url: '{{ route('admin.getCitiesByState') }}',
            type: 'GET',
            data: { state_id: stateID },
            success: function (data) {
                let options = '<option value="">{{ __("branch.select_city") }}</option>';
                $.each(data, function (key, city) {
                    options += `<option value="${city.id}">${city.name}</option>`;
                });
                $('select[name="city_id"]').html(options);
            }
        });
    });
});
</script>
@endsection
