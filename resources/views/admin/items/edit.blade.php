@extends('layouts.app')

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('item.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.items.index') }}" class="text-muted">
                <i class="fas fa-boxes mr-1"></i> {{ __('item.titles.items') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('item.actions.edit') }}</span>
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

            <form method="POST" action="{{ route('admin.items.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>{{ __('item.fields.name_en') }}</label>
                        <input type="text" name="name_en" class="form-control" value="{{ $item->name_en }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('item.fields.name_ar') }}</label>
                        <input type="text" name="name_ar" class="form-control" value="{{ $item->name_ar }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('item.fields.system') }}</label>
                        <select name="system_id" class="form-control" required>
                            @foreach ($systems as $system)
                                <option value="{{ $system->id }}" {{ $item->system_id == $system->id ? 'selected' : '' }}>
                                    {{ $system->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('item.fields.currency') }}</label>
                        <select name="currency_id" class="form-control" required>
    @foreach ($currencies as $currency)
        <option
            value="{{ $currency->id }}"
            {{ $item->currency_id == $currency->id ? 'selected' : '' }}
            @if ($currency->code !== 'AED') disabled @endif
        >
            {{ $currency->code }}
        </option>
    @endforeach
</select>

                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('item.fields.price') }}</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $item->price }}" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label>{{ __('item.fields.active') }}</label><br>
                        <label class="radio-inline mr-3">
                            <input type="radio" name="active" value="1" {{ $item->active ? 'checked' : '' }}> {{ __('item.fields.yes') }}
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="active" value="0" {{ !$item->active ? 'checked' : '' }}> {{ __('item.fields.no') }}
                        </label>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> {{ __('item.actions.update') }}
                    </button>
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">
                        {{ __('item.actions.back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
