@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.view_record') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.roles.index') }}" class="text-muted">{{ __('titles.roles') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('titles.view_record') }}</span>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ __('titles.view_record') }}</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('columns.name') }}:</label>
                    <div>{{ $role->name }}</div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">{{ __('columns.slug') }}:</label>
                    <div>{{ $role->slug }}</div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">{{ __('columns.description') }}:</label>
                    <div>{{ $role->description ?? '-' }}</div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">{{ __('columns.system') }}:</label>
                    <div>{{ $role->system->name ?? __('columns.global') }}</div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">{{ __('columns.created_at') }}:</label>
                    <div>{{ $role->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-left"></i> {{ __('actions.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
