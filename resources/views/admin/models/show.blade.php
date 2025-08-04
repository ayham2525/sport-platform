@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.view_model') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.models.index') }}" class="text-muted">{{ __('titles.models') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('titles.view_model') }}</span>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ __('titles.view_model') }}</h3>
            </div>

            <div class="card-body">
                <div class="form-row">

                    <div class="form-group col-md-6 col-sm-12">
                        <label class="font-weight-bold">{{ __('columns.name') }}:</label>
                        <div>{{ $model->name }}</div>
                    </div>

                    <div class="form-group col-md-6 col-sm-12">
                        <label class="font-weight-bold">{{ __('columns.slug') }}:</label>
                        <div>{{ $model->slug }}</div>
                    </div>

                    <div class="form-group col-md-6 col-sm-12">
                        <label class="font-weight-bold">{{ __('columns.system') }}:</label>
                        <div>{{ $model->system->name ?? '-' }}</div>
                    </div>

                    <div class="form-group col-md-6 col-sm-12">
                        <label class="font-weight-bold">{{ __('columns.only_admin') }}:</label>
                        <div>
                            @if($model->only_admin)
                                <span class="badge badge-danger">{{ __('columns.yes') }}</span>
                            @else
                                <span class="badge badge-success">{{ __('columns.no') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="font-weight-bold">{{ __('columns.description') }}:</label>
                        <div>{{ $model->description ?? '-' }}</div>
                    </div>

                    <div class="form-group col-md-6 col-sm-12">
                        <label class="font-weight-bold">{{ __('columns.created_at') }}:</label>
                        <div>{{ $model->created_at->format('Y-m-d H:i') }}</div>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.models.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-left"></i> {{ __('actions.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
