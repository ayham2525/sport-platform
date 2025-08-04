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
            <span class="text-muted">{{ $item->name_en }}</span>
        </li>
    </ul>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-box mr-1"></i> {{ $item->name_en }}
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> {{ __('item.actions.edit') }}
                </a>
                <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('item.actions.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>{{ __('item.fields.name_en') }}:</strong>
                    <div>{{ $item->name_en }}</div>
                </div>
                <div class="col-md-6">
                    <strong>{{ __('item.fields.name_ar') }}:</strong>
                    <div>{{ $item->name_ar }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>{{ __('item.fields.system') }}:</strong>
                    <div>{{ $item->system->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>{{ __('item.fields.currency') }}:</strong>
                    <div>{{ $item->currency->label ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>{{ __('item.fields.price') }}:</strong>
                    <div>{{ number_format($item->price, 2) }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <strong>{{ __('item.fields.active') }}:</strong>
                    <div>
                        @if ($item->active)
                            <i class="fas fa-check text-success"></i> {{ __('item.fields.yes') }}
                        @else
                            <i class="fas fa-times text-danger"></i> {{ __('item.fields.no') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
