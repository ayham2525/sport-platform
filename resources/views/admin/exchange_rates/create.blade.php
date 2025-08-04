@extends('layouts.app')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="fas fa-home mr-1"></i> {{ __('exchange_rate.titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.exchange-rates.index') }}" class="text-muted">
            <i class="fas fa-money-bill-wave mr-1"></i> {{ __('exchange_rate.titles.list') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('exchange_rate.titles.create') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="exchange-form" method="POST" action="{{ route('admin.exchange-rates.store') }}">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
    <label>{{ __('exchange_rate.fields.base_currency') }}</label>
    <select name="base_currency" class="form-control" required>
        <option value="">{{ __('exchange_rate.actions.select') }}</option>
        @foreach($currencies as $currency)
            <option value="{{ $currency->code }}">{{ $currency->code }} - {{ $currency->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-4">
    <label>{{ __('exchange_rate.fields.target_currency') }}</label>
    <select name="target_currency" class="form-control" required>
        <option value="">{{ __('exchange_rate.actions.select') }}</option>
        @foreach($currencies as $currency)
            <option value="{{ $currency->code }}">{{ $currency->code }} - {{ $currency->name }}</option>
        @endforeach
    </select>
</div>

                <div class="form-group col-md-4">
                    <label>{{ __('exchange_rate.fields.rate') }}</label>
                    <input type="number" step="0.0001" name="rate" class="form-control" required>
                </div>

                <div class="form-group col-md-4">
                    <label>{{ __('exchange_rate.fields.fetched_at') }}</label>
                    <input type="datetime-local" name="fetched_at" class="form-control" required>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> {{ __('exchange_rate.actions.save') }}
                </button>
                <a href="{{ route('admin.exchange-rates.index') }}" class="btn btn-secondary">
                    {{ __('exchange_rate.actions.back') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('exchange-form').addEventListener('submit', function(event) {
    const base = document.querySelector('input[name="base_currency"]').value.trim().toUpperCase();
    const target = document.querySelector('input[name="target_currency"]').value.trim().toUpperCase();
    const rate = parseFloat(document.querySelector('input[name="rate"]').value);
    const fetched = document.querySelector('input[name="fetched_at"]').value;

    if (base === '' || target === '' || !fetched || isNaN(rate) || rate <= 0) {
        event.preventDefault();
        alert("{{ __('exchange_rate.messages.validation_error') }}");
    }
});
</script>
@endsection
