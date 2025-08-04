@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.new_record') }}</h5>
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
        <span class="text-muted">{{ __('titles.new_record') }}</span>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ __('titles.new_record') }}</h3>
            </div>

            <form action="{{ route('admin.models.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-row">

                        {{-- Name --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>{{ __('columns.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('columns.name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>{{ __('columns.slug') }} <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="{{ __('columns.slug') }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- System --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>{{ __('columns.system') }} <span class="text-danger">*</span></label>
                            <select name="system_id" class="form-control @error('system_id') is-invalid @enderror">
                                <option value="">{{ __('columns.select_system') }}</option>
                                @foreach($systems as $system)
                                    <option value="{{ $system->id }}" {{ old('system_id') == $system->id ? 'selected' : '' }}>
                                        {{ $system->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('system_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Only Admin --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>{{ __('columns.only_admin') }}</label>
                            <select name="only_admin" class="form-control @error('only_admin') is-invalid @enderror">
                                <option value="0" {{ old('only_admin') == '0' ? 'selected' : '' }}>{{ __('columns.no') }}</option>
                                <option value="1" {{ old('only_admin') == '1' ? 'selected' : '' }}>{{ __('columns.yes') }}</option>
                            </select>
                            @error('only_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group col-md-12">
                            <label>{{ __('columns.description') }}</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="{{ __('columns.description') }}">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="la la-check"></i> {{ __('actions.save') }}
                    </button>
                    <a href="{{ route('admin.models.index') }}" class="btn btn-secondary">
                        <i class="la la-arrow-left"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
