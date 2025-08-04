@extends('layouts.app')

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('currency.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.currencies.index') }}" class="text-muted">
                <i class="fas fa-coins mr-1"></i> {{ __('currency.titles.currencies') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('currency.actions.edit') }}</span>
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

            <form method="POST" action="{{ route('admin.currencies.update', $currency->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>{{ __('currency.fields.code') }}</label>
                        <input type="text" name="code" class="form-control" required value="{{ old('code', $currency->code) }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('currency.fields.name') }}</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name', $currency->name) }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('currency.fields.symbol') }}</label>
                        <input type="text" name="symbol" class="form-control" required value="{{ old('symbol', $currency->symbol) }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('currency.fields.active') }}</label>
                        <select name="active" class="form-control">
                            <option value="1" {{ old('active', $currency->active) == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                            <option value="0" {{ old('active', $currency->active) == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                        </select>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> {{ __('currency.actions.update') }}
                    </button>
                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                        {{ __('currency.actions.back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
