@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('uniform_requests.title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home"></i> {{ __('uniform_requests.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <i class="la la-tshirt"></i> {{ __('uniform_requests.title') }}
    </li>
</ul>
@endsection

@section('content')
<div class="container">

    {{-- Filter Section --}}
    <form method="GET" class="mb-4">
        <div class="row">
            @if(auth()->user()->role === 'full_admin')
            <div class="col-md-3">
                <label><i class="la la-network-wired text-muted mr-1"></i> {{ __('uniform_requests.fields.system') }}</label>
                <select name="system_id" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('uniform_requests.fields.system') }}</option>
                    @foreach($systems as $id => $name)
                    <option value="{{ $id }}" {{ request('system_id') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-3">
                <label><i class="la la-code-branch text-muted mr-1"></i> {{ __('uniform_requests.fields.branch') }}</label>
                <select name="branch_id" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('uniform_requests.fields.branch') }}</option>
                    @foreach($branches as $id => $name)
                    <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label><i class="la la-info-circle text-muted mr-1"></i> {{ __('uniform_requests.fields.status') }}</label>
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('uniform_requests.fields.status') }}</option>
                    @foreach(\App\Models\UniformRequest::STATUS_OPTIONS as $status => $label)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ __('uniform_requests.statuses.' . $status) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- NEW: Branch Status --}}
            <div class="col-md-3 mt-3 mt-md-0">
                <label><i class="la la-sitemap text-muted mr-1"></i> {{ __('uniform_requests.fields.branch_status') }}</label>
                <select name="branch_status" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('uniform_requests.select_branch_status') }}</option>
                    @foreach(\App\Models\UniformRequest::BRANCH_STATUS_OPTIONS as $bStatus => $label)
                    <option value="{{ $bStatus }}" {{ request('branch_status') == $bStatus ? 'selected' : '' }}>
                        {{ __('uniform_requests.branch_statuses.' . $bStatus) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- NEW: Office Status --}}
            <div class="col-md-3 mt-3">
                <label><i class="la la-building text-muted mr-1"></i> {{ __('uniform_requests.fields.office_status') }}</label>
                <select name="office_status" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('uniform_requests.select_office_status') }}</option>
                    @foreach(\App\Models\UniformRequest::OFFICE_STATUS_OPTIONS as $oStatus => $label)
                    <option value="{{ $oStatus }}" {{ request('office_status') == $oStatus ? 'selected' : '' }}>
                        {{ __('uniform_requests.office_statuses.' . $oStatus) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- NEW: Payment Method (free text) --}}
            <div class="col-md-3 mt-3">
                <label><i class="la la-credit-card text-muted mr-1"></i> {{ __('uniform_requests.fields.payment_method') }}</label>
                <input type="text" name="payment_method" value="{{ request('payment_method') }}" class="form-control" placeholder="{{ __('uniform_requests.select_payment_method') }}" />
            </div>

            {{-- Submit button for payment_method input (others auto-submit on change) --}}
            <div class="col-md-3 mt-3 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary">
                    <i class="la la-search"></i> {{ __('uniform_requests.actions.list') }}
                </button>
            </div>
        </div>
    </form>

    {{-- Status cards (main statuses) --}}
    <div class="row mb-4">
        @foreach(\App\Models\UniformRequest::STATUS_OPTIONS as $status => $label)
        <div class="col-md-3">
            <div class="card text-center border">
                <div class="card-body p-3">
                    <h6 class="text-muted mb-2">
                        <i class="la la-info-circle"></i> {{ __('uniform_requests.statuses.' . $status) }}
                    </h6>
                    <h4 class="font-weight-bold" id="status-count-{{ $status }}">
                        {{ $statusCounts[$status] ?? 0 }}
                    </h4>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('uniform_requests.list') }}</h3>
            <div class="card-toolbar">
                @if (PermissionHelper::hasPermission('create', App\Models\UniformRequest::MODEL_NAME))
                <a href="{{ route('admin.uniform-requests.create') }}" class="btn btn-primary">
                    <i class="la la-plus-circle"></i> {{ __('uniform_requests.new') }}
                </a>
                @endif
            </div>
        </div>

        <div class="card-body">

            @if(session('success'))
            <div class="alert alert-success">
                <i class="la la-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="pl-3 mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="la la-user text-muted mr-1"></i> {{ __('uniform_requests.fields.player') }}</th>
                            <th><i class="la la-box text-muted mr-1"></i> {{ __('uniform_requests.fields.item') }}</th>
                            <th><i class="la la-ruler-combined text-muted mr-1"></i> {{ __('uniform_requests.fields.size') }}</th>
                            <th><i class="la la-palette text-muted mr-1"></i> {{ __('uniform_requests.fields.color') }}</th>
                            <th><i class="la la-sort-numeric-up text-muted mr-1"></i> {{ __('uniform_requests.fields.quantity') }}</th>
                            <th><i class="la la-money-bill text-muted mr-1"></i> {{ __('uniform_requests.fields.amount') }}</th>
                            <th><i class="la la-coins text-muted mr-1"></i> {{ __('uniform_requests.fields.currency') }}</th>
                            <th><i class="la la-info-circle text-muted mr-1"></i> {{ __('uniform_requests.fields.status') }}</th>

                            {{-- NEW headers --}}
                            <th><i class="la la-sitemap text-muted mr-1"></i> {{ __('uniform_requests.fields.branch_status') }}</th>
                            <th><i class="la la-building text-muted mr-1"></i> {{ __('uniform_requests.fields.office_status') }}</th>
                            <th><i class="la la-credit-card text-muted mr-1"></i> {{ __('uniform_requests.fields.payment_method') }}</th>

                            <th><i class="la la-sticky-note text-muted mr-1"></i> {{ __('uniform_requests.fields.admin_remarks') }}</th>
                            <th><i class="la la-check-circle text-muted mr-1"></i> {{ __('uniform_requests.fields.approved_at') }}</th>
                            <th><i class="la la-shipping-fast text-muted mr-1"></i> {{ __('uniform_requests.fields.ordered_at') }}</th>
                            <th><i class="la la-box-open text-muted mr-1"></i> {{ __('uniform_requests.fields.delivered_at') }}</th>
                            <th><i class="la la-calendar-alt text-muted mr-1"></i> {{ __('uniform_requests.fields.requested_at') }}</th>
                            <th><i class="la la-edit text-muted mr-1"></i> {{ __('uniform_requests.actions.edit') }} / <i class="la la-trash text-muted mr-1"></i> {{ __('uniform_requests.actions.delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($uniformRequests as $index => $req)
                        <tr class="request-row-{{ $req->id }}" data-status="{{ $req->status }}">
                            <td>{{ $uniformRequests->firstItem() + $index }}</td>
                            <td>{{ optional($req->player->user)->name ?? '-' }}</td>
                            <td>{{ app()->getLocale() === 'ar' ? ($req->item->name_ar ?? '-') : ($req->item->name_en ?? '-') }}</td>
                            <td>{{ $req->size ?? '-' }}</td>
                            <td>
                                @php $color = $req->color ?: '#ffffff'; @endphp
                                <span style="display:inline-block;width:20px;height:20px;background-color:{{ $color }};border:1px solid #ccc;border-radius:4px;"></span>
                            </td>
                            <td>{{ $req->quantity ?? 0 }}</td>
                            <td>{{ $req->amount ?? '0.00' }}</td>
                            <td>{{ optional($req->currency)->code ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $req->status === 'delivered' ? 'success' : ($req->status === 'ordered' ? 'info' : ($req->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                    {{ __('uniform_requests.statuses.' . ($req->status ?? 'requested')) }}
                                </span>
                            </td>

                            {{-- NEW cells --}}
                            <td>
                                {{ $req->branch_status ? __('uniform_requests.branch_statuses.' . $req->branch_status) : '—' }}
                            </td>
                            <td>
                                {{ $req->office_status ? __('uniform_requests.office_statuses.' . $req->office_status) : '—' }}
                            </td>
                            <td>{{ $req->payment_method ?? '—' }}</td>

                            <td>{{ $req->admin_remarks ?? '-' }}</td>
                            <td>{{ $req->approved_at ? \Carbon\Carbon::parse($req->approved_at)->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $req->ordered_at ? \Carbon\Carbon::parse($req->ordered_at)->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $req->delivered_at ? \Carbon\Carbon::parse($req->delivered_at)->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('Y-m-d') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.uniform-requests.edit', $req->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('uniform_requests.actions.edit') }}">
                                    <i class="la la-edit"></i>
                                </a>
                                <form class="d-inline delete-form" data-id="{{ $req->id }}" data-url="{{ route('admin.uniform-requests.destroy', $req->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('uniform_requests.actions.delete') }}">
                                        <i class="la la-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="18" class="text-center text-muted">{{ __('uniform_requests.no_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $uniformRequests->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete-button', function() {
        const form = $(this).closest('form');
        const requestId = form.data('id');
        const url = form.data('url');

        Swal.fire({
            title: '{{ __("uniform_requests.confirm_delete_title") }}'
            , text: "{{ __('uniform_requests.confirm_delete_text') }}"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#f64e60'
            , cancelButtonColor: '#c4c4c4'
            , confirmButtonText: '{{ __("uniform_requests.confirm_delete_button") }}'
            , cancelButtonText: '{{ __("uniform_requests.cancel") }}'
            , customClass: {
                confirmButton: 'btn btn-danger'
                , cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url
                    , type: 'POST'
                    , data: {
                        _token: form.find('input[name="_token"]').val()
                        , _method: 'DELETE'
                    }
                    , success: function() {
                        const row = $('.request-row-' + requestId);
                        const status = row.data('status');
                        row.remove();
                        Swal.fire({
                            icon: 'success'
                            , title: '{{ __("uniform_requests.deleted_successfully") }}'
                            , showConfirmButton: false
                            , timer: 1500
                        });
                        const countElement = $('#status-count-' + status);
                        if (countElement.length) {
                            const newCount = Math.max(0, parseInt(countElement.text(), 10) - 1);
                            countElement.text(newCount);
                        }
                    }
                    , error: function() {
                        Swal.fire({
                            icon: 'error'
                            , title: '{{ __("uniform_requests.delete_failed") }}'
                            , showConfirmButton: true
                        });
                    }
                });
            }
        });
    });

</script>
@endpush

