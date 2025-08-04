@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="fas fa-home mr-1"></i> {{ __('currency.titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('currency.titles.currencies') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-coins mr-1"></i> {{ __('currency.titles.currencies') }}
        </h3>
        <div class="card-toolbar">
            @if (PermissionHelper::hasPermission('create', App\Models\Currency::MODEL_NAME))
            <a href="{{ route('admin.currencies.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-1"></i> {{ __('currency.actions.add') }}
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        {{-- Success Message --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="la la-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="la la-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('currency.fields.code') }}</th>
                        <th>{{ __('currency.fields.name') }}</th>
                        <th>{{ __('currency.fields.symbol') }}</th>
                        <th>{{ __('currency.fields.active') }}</th>
                        <th>{{ __('currency.fields.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($currencies as $index => $currency)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $currency->code }}</td>
                        <td>{{ $currency->name }}</td>
                        <td>{{ $currency->symbol }}</td>
                        <td>
                            @if ($currency->active)
                            <i class="la la-check"></i>
                            @else
                            <i class="la la-times"></i>
                            @endif
                        </td>
                        <td nowrap>
                            @if (PermissionHelper::hasPermission('update', App\Models\Currency::MODEL_NAME))
                            <a href="{{ route('admin.currencies.edit', $currency->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('currency.actions.edit') }}">
                                <i class="la la-edit"></i>
                            </a>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('currency.messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

