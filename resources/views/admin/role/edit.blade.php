@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.edit_record') }}</h5>
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
        <span class="text-muted">{{ __('titles.edit_record') }}</span>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ __('titles.edit_record') }}</h3>
            </div>

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    {{-- Name --}}
                    <div class="form-group">
                        <label>{{ __('columns.name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('columns.name') }}" value="{{ old('name', $role->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="form-group">
                        <label>{{ __('columns.slug') }} <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="{{ __('columns.slug') }}" value="{{ old('slug', $role->slug) }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- System --}}
                    <div class="form-group">
                        <label>{{ __('columns.system') }}</label>
                        <select name="system_id" class="form-control @error('system_id') is-invalid @enderror">
                            <option value="">{{ __('columns.global') }}</option>
                            @foreach($systems as $system)
                                <option value="{{ $system->id }}" {{ old('system_id', $role->system_id) == $system->id ? 'selected' : '' }}>
                                    {{ $system->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('system_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label>{{ __('columns.description') }}</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="{{ __('columns.description') }}">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="la la-save"></i> {{ __('actions.save') }}
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="la la-arrow-left"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
