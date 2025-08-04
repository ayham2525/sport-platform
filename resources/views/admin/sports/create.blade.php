@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-dumbbell  mr-1"></i> {{ __('sport.actions.add') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home  mr-1"></i> {{ __('sport.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.sports.index') }}" class="text-muted">
                <i class="fas fa-dumbbell mr-1"></i> {{ __('sport.titles.sports') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('sport.actions.add') }}</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus  mr-1"></i> {{ __('sport.actions.add') }}
            </h3>
        </div>

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

            <form action="{{ route('admin.sports.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label><i class="fas fa-font mr-1"></i> {{ __('sport.fields.name_en') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-globe  mr-1"></i> {{ __('sport.fields.name_ar') }}</label>
                    <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') }}">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left  mr-1"></i> {{ __('sport.fields.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-icons  mr-1"></i> {{ __('sport.fields.icon') }}</label>
                    <input type="text" name="icon" class="form-control" placeholder="e.g. fas fa-futbol" value="{{ old('icon') }}">
                </div>

                <div class="form-group">
                <label class="d-block font-weight-bold">{{ __('sport.status.active') }}</label>
                <span class="switch switch-outline switch-icon switch-success">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </span>
            </div>


                <div class="form-group text-right mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> {{ __('sport.actions.save') }}
                    </button>
                    <a href="{{ route('admin.sports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('sport.actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
