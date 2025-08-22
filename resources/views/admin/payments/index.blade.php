@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')
<style>
    .table-nowrap td,
    .table-nowrap th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .table-nowrap td {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px;
        /* adjust as needed */
        font-size: 12px;
    }

</style>
@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="fas fa-home mr-1"></i> {{ __('item.titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-money-check-alt mr-1"></i> {{ __('payment.titles.payments_list') }}
    </li>
</ul>
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-body">

        <form method="GET" class="mb-4">
            <div class="form-row row py-5">
                <div class="col-12 col-md mb-2">
                    <label><i class="fas fa-network-wired mr-1"></i> {{ __('payment.filters.select_system') }}</label>
                    <select name="system_id" class="form-control">
                        <option value="">{{ __('payment.filters.select_system') }}</option>
                        @foreach ($systems as $system)
                        <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>
                            {{ $system->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md mb-2">
                    <label><i class="fas fa-building mr-1"></i> {{ __('payment.filters.select_branch') }}</label>
                    <select name="branch_id" class="form-control">
                        <option value="">{{ __('payment.filters.select_branch') }}</option>
                        @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md mb-2">
                    <label><i class="fas fa-university mr-1"></i> {{ __('payment.filters.select_academy') }}</label>
                    <select name="academy_id" class="form-control">
                        <option value="">{{ __('payment.filters.select_academy') }}</option>
                        @foreach ($academies as $academy)
                        <option value="{{ $academy->id }}" {{ request('academy_id') == $academy->id ? 'selected' : '' }}>
                            {{ $academy->name_en }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md mb-2">
                    <label><i class="fas fa-search mr-1"></i> {{ __('payment.filters.search_player') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('payment.filters.search_player') }}" value="{{ request('search') }}">
                </div>
                 <div class="col-12 col-md mb-2">
                    <label><i class="fas fa-search mr-1"></i> {{ __('payment.filters.player_id') }}</label>
                    <input type="text" name="player_id" class="form-control" placeholder="{{ __('payment.filters.player_id') }}" value="{{ request('player_id') }}">
                </div>

                <div class="col-12 col-md-auto d-flex align-items-end mb-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter mr-1"></i> {{ __('payment.actions.filter') }}
                    </button>
                </div>
            </div>
        </form>
        @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

        <div class="mb-4 text-right">
            @if (PermissionHelper::hasPermission('create', App\Models\Player::MODEL_NAME))
            <a href="{{ route('admin.payments.create') }}" class="btn btn-success">
                <i class="fas fa-plus mr-1"></i> {{ __('payment.actions.create') }}
            </a>
            @endif
            @if (PermissionHelper::hasPermission('export', App\Models\Payment::MODEL_NAME))
                <a href="{{ route('admin.payments.export', request()->query()) }}" class="btn btn-outline-info">
                    <i class="fas fa-file-excel mr-1"></i> {{ __('payment.actions.export') }}
                </a>
            @endif
        </div>


        <div class="table-responsive">
            <table class="table table-bordered table-hover table-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                         <th>{{ __('player.fields.id') }}</th>
                        <th><i class="fas fa-user"></i> {{ __('payment.fields.player') }}</th>
                        <th><i class="fas fa-cube"></i> {{ __('payment.fields.program') }}</th>
                        <th><i class="fas fa-wallet"></i> {{ __('payment.fields.amount') }}</th>
                        <th><i class="fas fa-check-circle"></i> {{ __('payment.fields.paid') }}</th>
                        <th><i class="fas fa-hourglass-half"></i> {{ __('payment.fields.remaining') }}</th>
                        <th><i class="fas fa-credit-card"></i> {{ __('payment.fields.payment_method') }}</th>
                        <th><i class="fas fa-flag"></i> {{ __('payment.fields.status') }}</th>
                        <th><i class="fas fa-cogs"></i> {{ __('payment.fields.actions') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + $payments->firstItem() }}</td>
                         <td>{{ $payment->player_id}}</td>
                        <td>{{ $payment->player->user->name ?? '-' }}</td>
                        <td>{{ $payment->program->name_en ?? '-' }}</td>
                        <td>{{ $payment->total_price }} {{ $payment->currency }}</td>
                        <td>{{ $payment->paid_amount }}</td>
                        <td>{{ $payment->remaining_amount }}</td>
                        @php
                        $methodName = $payment->paymentMethod->name ?? null;

                        $icons = [
                        'Cash' => 'fas fa-money-bill-wave',
                        'Credit Card' => 'fas fa-credit-card',
                        'Debit Card' => 'fas fa-credit-card',
                        'Bank Transfer' => 'fas fa-university',
                        'Apple Pay' => 'fab fa-apple',
                        'Google Pay' => 'fab fa-google',
                        'PayPal' => 'fab fa-paypal',
                        'Cheque' => 'fas fa-file-invoice-dollar',
                        'Installments' => 'fas fa-calendar-alt',
                        ];

                        $icon = $icons[$methodName] ?? 'fas fa-question-circle';
                        @endphp

                        <td class="text-center" title="{{ $methodName }}">
                            <i class="{{ $icon }}"></i>
                        </td>

                        <td>
                            @php
                            $statusMap = [
                            'paid' => ['label' => __('payment.status.paid'), 'icon' => 'fas fa-check'],
                            'partial' => [
                            'label' => __('payment.status.partial'),
                            'icon' => 'fas fa-adjust',
                            ],
                            'pending' => [
                            'label' => __('payment.status.pending'),
                            'icon' => 'fas fa-clock',
                            ],
                            ];
                            $status = $payment->status;
                            $data = $statusMap[$status] ?? [
                            'label' => ucfirst($status),
                            'icon' => 'fas fa-question',
                            ];
                            @endphp
                            <span class="d-inline-flex align-items-center" style="font-size: 0.875rem;">
                                <i class="{{ $data['icon'] }} mr-1"></i> {{ $data['label'] }}
                            </span>
                        </td>
                        <td>
                            @if (PermissionHelper::hasPermission('update', App\Models\Player::MODEL_NAME))
                            <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('payment.actions.edit') }}">
                                <i class="la la-edit"></i>
                            </a>
                            @endif
                             @if (PermissionHelper::hasPermission('delete', App\Models\Payment::MODEL_NAME))
                            <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('payment.actions.delete') }}">
                                    <i class="la la-trash"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('payment.actions.invoice') }}">
                                <i class="la la-file-pdf"></i>
                            </a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="mt-4">
            {{ $payments->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: '{{ __("payment.confirm.title") }}'
                , text: '{{ __("payment.confirm.message") }}'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonText: '{{ __("payment.confirm.confirm_button") }}'
                , cancelButtonText: '{{ __("payment.confirm.cancel_button") }}'
                , reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST'
                            , headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                                , 'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                            , }
                            , body: formData
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Request failed');
                            return response.json().catch(() => ({})); // in case no JSON returned
                        })
                        .then(() => {
                            Swal.fire({
                                icon: 'success'
                                , title: '{{ __("payment.messages.deleted_title") }}'
                                , text: '{{ __("payment.messages.deleted_message") }}'
                                , timer: 1500
                                , showConfirmButton: false
                            }).then(() => location.reload());
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error'
                                , title: '{{ __("payment.messages.error_title") }}'
                                , text: '{{ __("payment.messages.error_message") }}'
                            });
                        });
                }
            });
        });
    });

</script>


@endsection

