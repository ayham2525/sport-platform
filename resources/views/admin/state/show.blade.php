@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-map mr-1"></i> {{ __('state.show') }}: {{ $state->name }}
    </h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-dashboard mr-1"></i> {{ __('state.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.states.index') }}" class="text-muted">
            <i class="la la-map mr-1"></i> {{ __('state.index_title') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">
            <i class="la la-eye mr-1"></i> {{ __('state.show') }}
        </span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="la la-map mr-2"></i> {{ __('state.details') }}: {{ $state->name }}
                </h3>
            </div>

            <div class="card-body">
                @php
                    $displayClass = 'form-control form-control-solid bg-light';
                @endphp

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        <i class="la la-dot-circle-o mr-1"></i> {{ __('state.name') }}
                    </label>
                    <div class="col-md-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $state->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        <i class="la la-flag mr-1"></i> {{ __('state.country') }}
                    </label>
                    <div class="col-md-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $state->country->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        <i class="la la-toggle-on mr-1"></i> {{ __('state.status') }}
                    </label>
                    <div class="col-md-6 d-flex align-items-center">
                        <span class="badge badge-{{ $state->is_active ? 'success' : 'danger' }}">
                            {{ $state->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        <i class="la la-calendar-alt mr-1"></i> {{ __('messages.created_at') }}
                    </label>
                    <div class="col-md-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $state->created_at->format('Y-m-d H:i') }}" readonly>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('admin.states.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-left mr-1"></i> {{ __('actions.back') }}
                </a>
                <a href="{{ route('admin.states.edit', $state->id) }}" class="btn btn-primary">
                    <i class="la la-edit mr-1"></i> {{ __('actions.edit') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
