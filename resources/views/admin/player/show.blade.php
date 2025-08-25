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
        max-width: 200px;
        /* adjust as needed */
        font-size: 12px
    }

</style>
@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">
    <i class="fas fa-user text-info mr-1"></i> {{ __('player.titles.view_player') }}
</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="fas fa-home mr-1"></i> {{ __('player.titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.players.index') }}" class="text-muted">
            <i class="fas fa-users mr-1"></i> {{ __('player.titles.players') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('player.titles.view_player') }}</span>
    </li>
</ul>
@endsection


@section('content')
<div class="container-fluid">

    {{-- Player Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="mb-3">
            <i class="la la-user-circle text-info mr-2"></i> {{ $player->user->name }}
        </h4>
        <div>
            <a href="{{ route('admin.players.edit', $player->id) }}" class="btn btn-sm btn-primary mb-2">
                <i class="la la-edit"></i> {{ __('player.actions.edit') }}
            </a>
            <a href="{{ route('admin.players.index') }}" class="btn btn-sm btn-secondary mb-2">
                <i class="la la-arrow-left"></i> {{ __('player.actions.back') }}
            </a>
        </div>
    </div>

    {{-- Basic Info --}}
    <div class="card mb-4">
        <div class="card-header bg-white text-gray-800">
            <i class="la la-info-circle mr-1 text-primary"></i>
            <strong>{{ __('player.sections.basic_info') }}</strong>
        </div>
        <div class="card-body row">
            @if (session('success'))
            <div class="alert alert-success col-12">
                <strong><i class="la la-check-circle"></i> {{ __('player.messages.success') }}</strong>
                <p class="mb-0 mt-2">{{ session('success') }}</p>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger col-12">
                <strong><i class="la la-exclamation-triangle"></i>
                    {{ __('player.messages.something_went_wrong') }}</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <script>
                $(document).ready(function() {
                    $('#paymentModal').modal('show');
                });

            </script>
            @endif



            <div class="col-lg-6">
                <p><i class="la la-id-badge"></i> <strong>ID:</strong> {{ $player->id }}</p>
                <p><i class="la la-envelope"></i> <strong>{{ __('player.fields.email') }}:</strong>
                    {{ $player->user->email }}</p>
                <p><i class="la la-birthday-cake"></i> <strong>{{ __('player.fields.birth_date') }}:</strong>
                    {{ $player->birth_date }}</p>
                <p><i class="la la-venus-mars"></i> <strong>{{ __('player.fields.gender') }}:</strong>
                    {{ __('player.fields.' . $player->gender) }}</p>
                <p><i class="la la-clock"></i> <strong>{{ __('player.fields.created_at') }}:</strong>
                    {{ $player->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div class="col-lg-6">
                <p><i class="la la-building"></i> <strong>{{ __('player.fields.branch') }}:</strong>
                    {{ $player->branch->name ?? '-' }}</p>

                <p><i class="la la-university"></i> <strong>{{ __('player.fields.academy') }}:</strong>
                    {{ $player->academy->name_en ?? '-' }}</p>

                <p><i class="la la-flag"></i> <strong>{{ __('player.fields.nationality') }}:</strong>
                    {{ $player->nationality->name_en ?? '-' }}</p>

                <p><i class="la la-futbol"></i> <strong>{{ __('player.fields.sport') }}:</strong>
                    {{ $player->sport->name_en ?? '-' }}</p>

                <p><i class="la la-code"></i> <strong>{{ __('player.fields.player_code') }}:</strong>
                    {{ $player->player_code }}</p>

                <p><i class="la la-cube"></i> <strong>{{ __('player.fields.program') }}:</strong>
                    @if($player->programs->count())
                    {{ $player->programs->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')->join(', ') }}
                    @else
                    -
                    @endif
                </p>
                <p><i class="la la-user"></i> <strong>{{ __('player.fields.player_status') }}:</strong>
                    @php
                    $status = $player->status ?? 'active';
                    $badge = [
                    'active' => 'success',
                    'expired' => 'danger',
                    'stopped' => 'secondary',
                    ][$status] ?? 'light';

                    $icon = [
                    'active' => 'la la-check-circle',
                    'expired' => 'la la-times-circle',
                    'stopped' => 'la la-pause-circle',
                    ][$status] ?? 'la la-minus-circle';
                    @endphp

                    <span class="badge badge-{{ $badge }}">
                        <i class="{{ $icon }} text-white "></i>
                        {{ __('player.status.' . $status) }}
                    </span>
                </p>
                <p>
            </div>

        </div>
    </div>

    {{-- Sizes & Guardian --}}
    <div class="card mb-4">
        <div class="card-header bg-white text-gray-800">
            <i class="la la-user-tag mr-1 text-primary"></i>
            <strong>{{ __('player.sections.details') }}</strong>
        </div>
        <div class="card-body row">
            <div class="col-lg-6">
                <p><i class="la la-tshirt"></i> <strong>{{ __('player.fields.shirt_size') }}:</strong>
                    {{ $player->shirt_size }}</p>
                <p><i class="la la-th-large"></i> <strong>{{ __('player.fields.shorts_size') }}:</strong>
                    {{ $player->shorts_size }}</p>
                <p><i class="la la-shoe-prints"></i> <strong>{{ __('player.fields.shoe_size') }}:</strong>
                    {{ $player->shoe_size }}</p>
            </div>
            <div class="col-lg-6">
                <p><i class="la la-user"></i> <strong>{{ __('player.fields.guardian_name') }}:</strong>
                    {{ $player->guardian_name }}</p>
                <p><i class="la la-phone"></i> <strong>{{ __('player.fields.guardian_phone') }}:</strong>
                    {{ $player->guardian_phone }}</p>
            </div>
        </div>
    </div>

    {{-- Extra Info --}}
    <div class="card mb-4">
        <div class="card-header bg-white text-gray-800">
            <i class="la la-plus-circle mr-1 text-primary"></i>
            <strong>{{ __('player.sections.additional') }}</strong>
        </div>
        <div class="card-body">
            <p><i class="la la-school"></i> <strong>{{ __('player.fields.previous_school') }}:</strong>
                {{ $player->previous_school }}</p>
            <p><i class="la la-users"></i> <strong>{{ __('player.fields.previous_academy') }}:</strong>
                {{ $player->previous_academy }}</p>
            <p><i class="la la-notes-medical"></i> <strong>{{ __('player.fields.medical_notes') }}:</strong>
                {{ $player->medical_notes }}</p>
            <p><i class="la la-comment-dots"></i> <strong>{{ __('player.fields.remarks') }}:</strong>
                {{ $player->remarks }}</p>
        </div>
    </div>

    {{-- Uniform Requests --}}
    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center bg-white text-gray-800">
            <div>
                <i class="la la-tshirt mr-1 text-primary"></i>
                <strong>{{ __('uniform_requests.titles.uniform_requests') }}</strong>
            </div>
            <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addUniformModal">
                <i class="la la-plus-circle"></i> {{ __('uniform_requests.actions.add') }}
            </a>
        </div>

        <div class="card-body">
            @if(($uniformRequests ?? collect())->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('uniform_requests.fields.item') }}</th>
                            <th>{{ __('uniform_requests.fields.size') }}</th>
                            <th>{{ __('uniform_requests.fields.color') }}</th>
                            <th>{{ __('uniform_requests.fields.quantity') }}</th>
                            <th>{{ __('uniform_requests.fields.amount') }}</th>
                            <th>{{ __('uniform_requests.fields.currency') }}</th>
                            <th>{{ __('uniform_requests.fields.status') }}</th>
                            <th>{{ __('uniform_requests.fields.branch_status') }}</th>
                            <th>{{ __('uniform_requests.fields.office_status') }}</th>
                            <th>{{ __('uniform_requests.fields.stock_status') }}</th>

                            <th>{{ __('uniform_requests.fields.payment_method') }}</th>
                            <th>{{ __('uniform_requests.fields.request_date') }}</th>
                            <th>{{ __('uniform_requests.fields.approved_at') }}</th>
                            <th>{{ __('uniform_requests.fields.ordered_at') }}</th>
                            <th>{{ __('uniform_requests.fields.delivered_at') }}</th>
                            <th>{{ __('uniform_requests.fields.notes') }}</th>
                            <th>{{ __('player.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uniformRequests as $i => $req)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($req->item)->{app()->getLocale() === 'ar' ? 'name_ar' : 'name_en'} ?? '-' }}</td>
                            <td>{{ $req->size ?? '-' }}</td>
                            <td>
                                @php $color = $req->color ?: '#cccccc'; @endphp
                                <span class="d-inline-block rounded" style="width:20px;height:20px;background: {{ $color }}; border: 1px solid #ddd;"></span>
                                <small class="text-muted ml-1">{{ $color }}</small>
                            </td>
                            <td>{{ $req->quantity }}</td>
                            <td>{{ number_format((float)$req->amount, 2) }}</td>
                            <td>{{ optional($req->currency)->code ?? '-' }}</td>
                            <td>{{ $req->status_label }}</td>
                            <td>{{ $req->branch_status_label }}</td>
                            <td>{{ $req->office_status_label }}</td>
                            <td>
                                @php
                                $stock = $req->stock_status ?? 'pending';
                                $badgeClass = match($stock) {
                                'in_stock' => 'success',
                                'reserved' => 'warning',
                                'out_of_stock' => 'danger',
                                default => 'secondary',
                                };
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">
                                    {{ __('uniform_requests.stock_statuses.' . $stock) }}
                                </span>
                            </td>
                            <td>{{ $req->payment_method ?? '-' }}</td>
                            <td>{{ optional($req->request_date)->format('Y-m-d') }}</td>
                            <td>{{ optional($req->approved_at)->format('Y-m-d H:i') }}</td>
                            <td>{{ optional($req->ordered_at)->format('Y-m-d H:i') }}</td>
                            <td>{{ optional($req->delivered_at)->format('Y-m-d H:i') }}</td>
                            <td>{{ $req->notes }}</td>
                            <td>
                                <a href="{{ route('admin.uniform-requests.edit', $req->id) }}" class="btn btn-sm btn-clean text-primary" title="{{ __('player.actions.edit') }}">
                                    <i class="la la-edit"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-clean text-danger delete-uniform-btn" data-id="{{ $req->id }}" title="{{ __('player.actions.delete') }}">
                                    <i class="la la-trash"></i>
                                </button>

                                <form id="delete-form-{{ $req->id }}" action="{{ route('admin.uniform-requests.destroy', $req->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            @else
            <p class="text-muted mb-0">{{ __('uniform_requests.messages.no_requests') }}</p>
            @endif
        </div>
    </div>




    {{-- Uniform Requests --}}
    <div class="modal fade" id="addUniformModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.uniform-requests.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="la la-plus-circle"></i> {{ __('uniform_requests.actions.add') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <input type="hidden" name="system_id" value="{{ $player->branch->system_id }}">
                    <input type="hidden" name="branch_id" value="{{ $player->branch_id }}">
                    <input type="hidden" name="player_id" value="{{ $player->id }}">

                    <div class="form-group col-md-12">
                        <label>{{ __('uniform_requests.fields.item') }}</label>
                        <select name="item_id" class="form-control" required>
                            @foreach ($items as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('uniform_requests.fields.size') }}</label>
                        <select name="size" class="form-control" required>
                            <option value="">{{ __('uniform_requests.select_size') }}</option>
                            @foreach (__('uniform_requests.sizes') as $key => $label)
                            <option value="{{ $key }}" {{ $player->shirt_size == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('uniform_requests.fields.color') }}</label>
                        <input type="color" name="color" class="form-control" value="#D71920" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('uniform_requests.fields.quantity') }}</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('uniform_requests.fields.amount') }}</label>
                        <input type="number" name="amount" step="0.01" class="form-control" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label>{{ __('uniform_requests.fields.currency') }}</label>
                        <select name="currency_id" class="form-control" required>
                            @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>{{ __('uniform_requests.fields.notes') }}</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('uniform_requests.actions.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="la la-save"></i> {{ __('uniform_requests.actions.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Payments --}}
    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center bg-white text-gray-800">

            <div>
                <i class="la la-money-bill-wave mr-1 text-success"></i>
                <strong>{{ __('player.sections.payments') }}</strong>
            </div>
            <a href="javascript:void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#paymentModal">
                <i class="la la-plus-circle"></i> {{ __('player.actions.add_payment') }}
            </a>

        </div>
        <div class="card-body">
            @if ($player->payments->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('player.fields.branch') }}</th>
                            <th>{{ __('player.fields.total_price') }}</th>
                            <th>{{ __('player.fields.status') }}</th>
                            <th>{{ __('player.fields.payment_date') }}</th>
                            <th>{{ __('player.fields.start_date') }}</th>
                            <th>{{ __('player.fields.end_date') }}</th>
                            <th>{{ __('player.fields.discount') }}</th>
                            <th>{{ __('player.fields.reset_number') }}</th>
                            <th>{{ __('player.fields.payment_method') }}</th>
                            <th>{{ __('player.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($player->payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->branch->name ?? '-' }}</td>
                            <td>{{ number_format($payment->total_price, 2) }} {{ $payment->currency }}</td>
                            <td>{{ __('payment.status.' . $payment->status) }}</td>
                            <td>{{ optional($payment->payment_date)->format('Y-m-d') }}</td>
                            <td>{{ optional($payment->start_date)->format('Y-m-d') }}</td>
                            <td>{{ optional($payment->end_date)->format('Y-m-d') }}</td>
                            <td>{{ number_format($payment->discount, 2) }}</td>
                            <td>{{ $payment->reset_number ?? '-' }}</td>
                            <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-sm btn-clean text-primary" title="{{ __('player.actions.edit') }}">
                                    <i class="la la-edit"></i>
                                </a>

                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-clean text-info" title="{{ __('player.actions.view') }}">
                                    <i class="la la-eye"></i>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted mb-0">{{ __('player.messages.no_payments') }}</p>
            @endif
        </div>
    </div>

    {{-- Include Modal --}}
    @include('admin.player._payment_modal')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-uniform-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                Swal.fire({
                    title: "{{ __('messages.confirm_delete_title') }}"
                    , text: "{{ __('messages.confirm_delete_text') }}"
                    , icon: "warning"
                    , showCancelButton: true
                    , confirmButtonColor: "#d33"
                    , cancelButtonColor: "#3085d6"
                    , confirmButtonText: "{{ __('messages.yes_delete') }}"
                    , cancelButtonText: "{{ __('messages.cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });
    });

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        if (isNaN(date)) return '';
        return date.toISOString().slice(0, 10);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-payment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.dataset.id;

                fetch(`/admin/player-payments/${paymentId}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        const form = document.getElementById('paymentForm');

                        // Set form action and method
                        form.action = `/admin/player-payments/${paymentId}`;
                        document.getElementById('formMethod').value = 'PUT';

                        // Handle existing receipt
                        const receiptWrapper = document.getElementById('existing-receipt');
                        if (data.receipt_path) {
                            receiptWrapper.style.display = 'block';
                            receiptWrapper.querySelector('a').href =
                                `/storage/${data.receipt_path}`;
                            receiptWrapper.querySelector('a').textContent =
                                '📄 View current receipt';
                        } else {
                            receiptWrapper.style.display = 'none';
                        }

                        // Fill form fields safely
                        form.querySelector('[name=payment_date]').value = formatDate(data
                            .payment_date);
                        form.querySelector('[name=start_date]').value = formatDate(data
                            .start_date);
                        form.querySelector('[name=end_date]').value = formatDate(data
                            .end_date);
                        form.querySelector('[name=status_student]').value = data
                            .status_student ? ? '';
                        form.querySelector('[name=status]').value = data.status ? ? '';
                        form.querySelector('[name=payment_method_id]').value = data
                            .payment_method_id ? ? '';
                        form.querySelector('[name=total_price]').value = data.total_price ? ?
                            '';
                        form.querySelector('[name=discount]').value = data.discount ? ? '';
                        form.querySelector('[name=reset_number]').value = data
                            .reset_number ? ? '';
                        form.querySelector('[name=class_time_from]').value = (data
                            .class_time_from || '').slice(0, 5);
                        form.querySelector('[name=class_time_to]').value = (data
                            .class_time_to || '').slice(0, 5);

                        form.querySelector('[name=note]').value = data.note ? ? '';
                    })
                    .catch(error => {
                        console.error('Error loading payment data:', error);
                    });
            });
        });

        // Reset form when modal is closed
        $('#paymentModal').on('hidden.bs.modal', function() {
            const form = document.getElementById('paymentForm');
            form.reset();
            form.action = `{{ route('admin.player_payments.store') }}`;
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('existing-receipt').style.display = 'none';
        });
    });

</script>

@endsection

