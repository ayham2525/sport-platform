@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('paymentMethod.titles.payment_methods') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('paymentMethod.breadcrumb.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.payment-methods.index') }}" class="text-muted">{{ __('paymentMethod.titles.payment_methods') }}</a>
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
                        {{ __('paymentMethod.titles.payment_methods') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('paymentMethod.subtitles.manage_methods') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\PaymentMethod::MODEL_NAME))
                    <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="fas fa-plus-circle"></i> {{ __('paymentMethod.actions.new_method') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('paymentMethod.actions.close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('paymentMethod.actions.close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <table class="table table-separate table-head-custom table-checkable" id="payment-methods-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('paymentMethod.fields.name_en') }}</th>
                            <th>{{ __('paymentMethod.fields.name_ar') }}</th>
                            <th>{{ __('paymentMethod.fields.name_ur') }}</th>
                            <th>{{ __('paymentMethod.fields.description') }}</th>
                            <th>{{ __('paymentMethod.fields.created_at') }}</th>
                            <th>{{ __('paymentMethod.fields.status') }}</th>
                            <th>{{ __('paymentMethod.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($paymentMethods as $method)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $method->name }}</td>
                                <td>{{ $method->name_ar ?? '-' }}</td>
                                <td>{{ $method->name_ur ?? '-' }}</td>
                                <td>{{ $method->description ?? '-' }}</td>
                                <td>{{ optional($method->created_at)->format('Y-m-d') ?? '—' }}</td>
                                <td>
                                    @if (!empty($method->is_active))
                                        <span class="badge badge-success">{{ __('paymentMethod.status.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('paymentMethod.status.inactive') }}</span>
                                    @endif
                                </td>
                                <td nowrap>
                                     @if (PermissionHelper::hasPermission('update', App\Models\PaymentMethod::MODEL_NAME))
                                    <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('paymentMethod.actions.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    @endif
                                     @if (PermissionHelper::hasPermission('delete', App\Models\PaymentMethod::MODEL_NAME))
                                    <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('paymentMethod.actions.delete') }}">
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

{{-- jQuery + SweetAlert + DataTables init (kept as-is) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#payment-methods-table')) {
        $('#payment-methods-table').DataTable().clear().destroy();
    }

    if ($.fn.DataTable) {
        $('#payment-methods-table').DataTable({
            paging: true,
            searching: true,
            responsive: true,
            autoWidth: false,
            language: {
                searchPlaceholder: "{{ __('paymentMethod.actions.search') }}",
                paginate: {
                    previous: "<i class='la la-angle-left'></i>",
                    next: "<i class='la la-angle-right'></i>"
                }
            }
        });
    }

    $('.delete-button').on('click', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');

        if (window.Swal) {
            Swal.fire({
                title: "{{ __('paymentMethod.messages.confirm_delete_title') }}",
                text: "{{ __('paymentMethod.messages.confirm_delete_text') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f64e60',
                cancelButtonColor: '#c4c4c4',
                confirmButtonText: "{{ __('paymentMethod.actions.confirm_delete') }}",
                cancelButtonText: "{{ __('paymentMethod.actions.cancel') }}",
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        } else {
            if (confirm("{{ __('paymentMethod.messages.confirm_delete_text') }}")) form.submit();
        }
    });
});
</script>
@endsection
