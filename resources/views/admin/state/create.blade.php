@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('state.create') }}</h5>
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
            <i class="la la-plus-circle mr-1"></i> {{ __('state.create') }}
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
                    <i class="la la-map mr-2"></i> {{ __('state.create') }}
                </h3>
            </div>

            <form action="{{ route('admin.states.store') }}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    @php $inputClass = 'form-control form-control-solid'; @endphp

                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6 py-2">
                            <label>
                                <i class="la la-dot-circle-o mr-1"></i> {{ __('state.name') }}
                            </label>
                            <input type="text" name="name"
                                   class="{{ $inputClass }} @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('state.name') }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-md-6 py-2">
                            <label>
                                <i class="la la-flag mr-1"></i> {{ __('state.country') }}
                            </label>
                            <select name="country_id" class="{{ $inputClass }} @error('country_id') is-invalid @enderror">
                                <option value="">{{ __('messages.select_country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6 py-2">
                            <label>
                                <i class="la la-toggle-on mr-1"></i> {{ __('state.status') }}
                            </label><br>
                            <span class="switch switch-outline switch-icon switch-success">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}/>
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="la la-check-circle mr-1"></i> {{ __('actions.save') }}
                    </button>
                    <a href="{{ route('admin.states.index') }}" class="btn btn-secondary">
                        <i class="la la-times-circle mr-1"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
