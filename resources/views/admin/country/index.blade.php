@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')
@section('content')
<!--begin::Subheader-->
@section('countries')
@section( 'page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.countries') }}</h5>
@endsection
@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-dashboard mr-1"></i> {{ __('titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.countries.index') }}" class="text-muted">
            <i class="la la-flag mr-1"></i> {{ __('titles.countries') }}
        </a>
    </li>
</ul>
@endsection

@endsection
<!--end::Subheader-->
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Card-->

        <div class="card card-custom gutter-b">
             @if (session('success'))
                    <div class="alert alert-custom alert-success alert-dismissible fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="la la-check-circle"></i></div>
                        <div class="alert-text">{{ __(session('success')) }}</div>
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
                        <div class="alert-text">{{ __(session('error')) }}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-times"></i></span>
                            </button>
                        </div>
                    </div>
                @endif
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('titles.countries') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('titles.countries_management') }}</span></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline mr-2  d-none">
                        <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                        <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>Export</button>
                        <!--begin::Dropdown Menu-->
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <!--begin::Navigation-->
                            <ul class="navi flex-column navi-hover py-2">
                                <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Choose an option:</li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-print"></i>
                                        </span>
                                        <span class="navi-text">Print</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-copy"></i>
                                        </span>
                                        <span class="navi-text">Copy</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-file-excel-o"></i>
                                        </span>
                                        <span class="navi-text">Excel</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-file-text-o"></i>
                                        </span>
                                        <span class="navi-text">CSV</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-file-pdf-o"></i>
                                        </span>
                                        <span class="navi-text">PDF</span>
                                    </a>
                                </li>
                            </ul>
                            <!--end::Navigation-->
                        </div>
                        <!--end::Dropdown Menu-->
                    </div>
                    <!--end::Dropdown-->
                    <!--begin::Button-->
                    @if (PermissionHelper::hasPermission('create', App\Models\Country::MODEL_NAME))
                    <a href="{{ route('admin.countries.create') }}" class="btn btn-primary font-weight-bolder">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <circle fill="#000000" cx="9" cy="15" r="6" />
                                    <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>New Record</a>
                        @endif
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
                    <thead>
                        <tr>
                        <th>{{ __('columns.id') }}</th>
                        <th>{{ __('columns.iso2') }}</th>
                        <th>{{ __('columns.country') }}</th>
                        <th>{{ __('columns.phone_code') }}</th>
                        <th>{{ __('columns.currency') }}</th>
                        <th>{{ __('columns.symbol') }}</th>
                        <th>{{ __('columns.native_name') }}</th>
                        <th>{{ __('columns.created_at') }}</th>
                        <th>{{ __('columns.status') }}</th>
                        <th>{{ __('columns.actions') }}</th>
                        <th></th>
                    </tr>

                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($countries as $country)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ strtoupper($country->iso2) }}</td>
                            <td>
                                {{ $country->name }}
                                @if ($country->flag)
                                <img src="{{ asset('images/admin/original/' . $country->flag) }}" alt="Flag" width="30" class="ml-2" />
                                @endif
                            </td>
                            <td>{{ $country->phone_code }}</td>
                            <td>{{ $country->currency }}</td>
                            <td>{{ $country->currency_symbol }}</td>
                            <td>{{ $country->name_native }}</td>
                            <td>{{ $country->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if ($country->is_active)
                                <span class="label label-inline label-light-success font-weight-bold">Active</span>
                                @else
                                <span class="label label-inline label-light-danger font-weight-bold">Inactive</span>
                                @endif
                            </td>
                            <td nowrap>
                           @if (PermissionHelper::hasPermission('view', App\Models\Country::MODEL_NAME))

                        <a href="{{ route('admin.countries.show', $country->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.view') }}">
                            <i class="la la-eye"></i>
                        </a>
                        @endif
                     @if (PermissionHelper::hasPermission('update', App\Models\Country::MODEL_NAME))

                        <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.edit') }}">
                            <i class="la la-edit"></i>
                        </a>
                        @endif
                                            @if (PermissionHelper::hasPermission('delete', App\Models\Country::MODEL_NAME))

                        <form action="{{ route('admin.countries.destroy', $country->id) }}" method="POST" class="delete-form d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('actions.delete') }}">
                                <i class="la la-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>

                            <td></td>
                        </tr>
                          @php $i+=1; @endphp
                        @endforeach
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Destroy and re-init DataTable safely
    if ($.fn.DataTable.isDataTable('#kt_datatable1')) {
        $('#kt_datatable1').DataTable().clear().destroy();
    }

    $('#kt_datatable1').DataTable({
        columnDefs: [{
            targets: -1,
            visible: false,
            searchable: false
        }]
    });

    // Confirmation before delete
    $('.delete-button').click(function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
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



<!--end::Entry-->
@endsection
