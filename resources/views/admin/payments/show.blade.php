@extends('layouts.app')

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('payment.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.payments.index') }}" class="text-muted">
                <i class="fas fa-money-bill-wave mr-1"></i> {{ __('payment.titles.payments_list') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('payment.actions.view') }}</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-receipt text-primary mr-2"></i>
            {{ __('payment.titles.view_payment') }}
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>{{ __('payment.fields.system') }}:</strong> {{ $payment->system->name ?? '-' }}</p>
                <p><strong>{{ __('payment.fields.branch') }}:</strong> {{ $payment->branch->name ?? '-' }}</p>
                <p><strong>{{ __('payment.fields.academy') }}:</strong> {{ $payment->academy->name_en ?? '-' }}</p>
                <p><strong>{{ __('payment.fields.player') }}:</strong> {{ $payment->player->user->name ?? '-' }}</p>
                <p><strong>{{ __('payment.fields.program') }}:</strong> {{ $payment->program->name_en ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>{{ __('payment.fields.category') }}:</strong> {{ __('payment.categories.' . $payment->category) }}</p>
                <p><strong>{{ __('payment.fields.status') }}:</strong> {{ __('payment.status.' . $payment->status) }}</p>
                <p><strong>{{ __('payment.fields.payment_method') }}:</strong> {{ $payment->paymentMethod->name ?? '-' }}</p>
                <p><strong>{{ __('payment.fields.total_price') }}:</strong> {{ number_format($payment->total_price, 2) }} {{ $payment->currency }}</p>
                <p><strong>{{ __('payment.fields.paid_amount') }}:</strong> {{ number_format($payment->paid_amount, 2) }}</p>
                <p><strong>{{ __('payment.fields.remaining_amount') }}:</strong> {{ number_format($payment->remaining_amount, 2) }}</p>
            </div>
        </div>

        @if ($payment->note)
            <hr>
            <p><strong>{{ __('payment.fields.note') }}:</strong> {{ $payment->note }}</p>
        @endif

        @if (!empty($payment->items))
            <hr>
            <h5>{{ __('payment.fields.items') }}</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('payment.fields.item') }}</th>
                        <th>{{ __('payment.fields.quantity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (json_decode($payment->items, true) as $item)
                        <tr>
                            <td>{{ \App\Models\Item::find($item['item_id'])->name_en ?? '-' }}</td>
                            <td>{{ $item['quantity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="text-right mt-4">
             <a href="{{ route('admin.players.show', $payment->player_id) }}" class="btn btn-info">
        <i class="la la-user"></i> {{ __('player.actions.view_player') }}
    </a>
            <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-primary">
                <i class="la la-edit"></i> {{ __('payment.actions.edit') }}
            </a>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                <i class="la la-arrow-left"></i> {{ __('payment.actions.back') }}
            </a>
        </div>
    </div>
</div>
@endsection
