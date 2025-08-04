@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-building-o mr-1"></i> {{ __('city.index_title') }}
    </h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-dashboard mr-1"></i> {{ __('city.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.cities.index') }}" class="text-muted">
            <i class="la la-building-o mr-1"></i> {{ __('city.index_title') }}
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
                    <h3 class="card-label">{{ __('city.index_title') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('city.index_subtitle') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                @if (PermissionHelper::hasPermission('create', App\Models\City::MODEL_NAME))
                    <a href="{{ route('admin.cities.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus mr-1"></i> {{ __('city.create_new') }}
                    </a>
                @endif
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="" class="mb-5">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-3 px-1 mb-2">
                            <select name="country_id" class="form-control" id="country_id">
                                <option value="">{{ __('city.select_country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-3 px-1 mb-2">
                            <select name="state_id" class="form-control" id="state_id">
                                <option value="">{{ __('city.select_state') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-3 px-1 mb-2">
                            <select name="status" class="form-control">
                                <option value="">{{ __('city.select_status') }}</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-3 px-1 mb-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter"></i> {{ __('actions.filter') }}
                            </button>
                        </div>
                    </div>
                </form>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <table class="table table-separate table-head-custom table-checkable" id="cities-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('city.name') }}</th>
                            <th>{{ __('city.state') }}</th>
                            <th>{{ __('city.country') }}</th>
                            <th>{{ __('city.created_at') }}</th>
                            <th>{{ __('city.status') }}</th>
                            <th>{{ __('actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cities as $index => $city)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->state->name ?? '-' }}</td>
                            <td>{{ $city->country->name ?? '-' }}</td>
                            <td>{{ $city->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge badge-{{ $city->is_active ? 'success' : 'danger' }}">
                                    {{ $city->is_active ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </td>
                            <td nowrap>
                                @if (PermissionHelper::hasPermission('update', App\Models\City::MODEL_NAME))
                                    <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                @endif
                                @if (PermissionHelper::hasPermission('delete', App\Models\City::MODEL_NAME))
                                    <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" class="delete-form d-inline-block">
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#cities-table')) {
            $('#cities-table').DataTable().clear().destroy();
        }

        $('#cities-table').DataTable({
            paging: true
            , searching: true
            , responsive: true
            , autoWidth: false
            , language: {
                searchPlaceholder: "Search..."
                , paginate: {
                    previous: "<i class='la la-angle-left'></i>"
                    , next: "<i class='la la-angle-right'></i>"
                }
            }
        });

        $(document).on('click', '.delete-button', function(e) {

            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?'
                , text: "This will delete the city permanently!"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#f64e60'
                , cancelButtonColor: '#c4c4c4'
                , confirmButtonText: 'Yes, delete it!'
                , customClass: {
                    confirmButton: 'btn btn-danger'
                    , cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $('#country_id').select2();
        $('#state_id').select2();

        $('#country_id').change(function() {
            let countryID = $(this).val();
            $.ajax({
            url: '{{ route('admin.getStatesByCountry') }}',
            type: 'GET',
            data: {
                country_id: countryID
            },
            success: function(data) {
                let options = '<option value="">Select State</option>';
                $.each(data, function(key, state) {
                    options += `<option value="${state.id}">${state.name}</option>`;
                });
                $('#state_id').html(options);
                $('#state_id').trigger('change');
            }
        });

    });
});

</script>

