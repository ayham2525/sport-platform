@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-building-o mr-1"></i> {{ __('city.create_title') }}
    </h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-dashboard mr-1"></i> {{ __('city.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.cities.index') }}" class="text-muted">
            <i class="la la-building-o mr-1"></i> {{ __('city.index_title') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">
            <i class="la la-plus-circle mr-1"></i> {{ __('city.create_title') }}
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
                    <i class="la la-plus-circle mr-1"></i> {{ __('city.create_title') }}
                </h3>
            </div>

            <!--begin::Form-->
            <form action="{{ route('admin.cities.store') }}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    @php $inputClass = 'form-control form-control-solid'; @endphp

                    <div class="form-group row">
                        {{-- City Name --}}
                        <div class="col-md-6 mb-4">
                            <label>
                                <i class="la la-city mr-1"></i> {{ __('city.name') }}
                            </label>
                            <input type="text" name="name"
                                   class="{{ $inputClass }} @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('city.name_placeholder') }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- State --}}
                        <div class="col-md-6 mb-4">
                            <label>
                                <i class="la la-map mr-1"></i> {{ __('city.state') }}
                            </label>
                            <select name="state_id" class="{{ $inputClass }} @error('state_id') is-invalid @enderror">
                                <option value="">{{ __('city.select_state') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }} ({{ $state->country->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group row">
                        <div class="col-md-6 mb-4">
                            <label>
                                <i class="la la-toggle-on mr-1"></i> {{ __('city.status') }}
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
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                        <i class="la la-times-circle mr-1"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
@endsection
