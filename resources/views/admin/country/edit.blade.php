@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('country.edit_title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-dashboard mr-1"></i> {{ __('country.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.countries.index') }}" class="text-muted">
            <i class="la la-flag mr-1"></i> {{ __('country.index_title') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">
            <i class="la la-edit mr-1"></i> {{ __('country.edit') }}
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
                    <i class="la la-edit mr-2"></i> {{ __('country.edit_title') }}: {{ $country->name }}
                </h3>
            </div>

            <form action="{{ route('admin.countries.update', $country->id) }}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    @php $inputClass = 'form-control form-control-solid'; @endphp

                    <div class="form-group row">
                        @foreach ([
                            'name' => __('country.name_en'),
                            'name_native' => __('country.name_native'),
                            'iso2' => __('country.iso2'),
                            'iso3' => __('country.iso3'),
                            'phone_code' => __('country.phone_code'),
                            'currency' => __('country.currency'),
                            'currency_symbol' => __('country.currency_symbol'),
                        ] as $field => $label)
                            <div class="col-sm-12 col-md-6 py-4">
                                <label>
                                    <i class="la la-dot-circle-o mr-1"></i> {{ $label }}
                                </label>
                                <input type="text" name="{{ $field }}"
                                    class="{{ $inputClass }} @error($field) is-invalid @enderror"
                                    value="{{ old($field, $country->$field) }}"
                                    placeholder="{{ __('country.enter') }} {{ strtolower($label) }}">
                                @error($field)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    {{-- Flag Upload --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>
                                <i class="la la-image mr-1"></i> {{ __('country.flag') }}
                            </label>
                            @if ($country->flag)
                                <div class="mb-2">
                                    <img src="{{ asset('images/admin/original/' . $country->flag) }}" width="40" alt="Flag">
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="flag" class="custom-file-input @error('flag') is-invalid @enderror" id="flagUpload">
                                <label class="custom-file-label" for="flagUpload">{{ __('country.choose_file') }}</label>
                                @error('flag')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Status Switch --}}
                        <div class="col-md-6">
                            <label>
                                <i class="la la-toggle-on mr-1"></i> {{ __('country.status') }}
                            </label>
                            <br>
                            <span class="switch switch-outline switch-icon switch-success">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $country->is_active) ? 'checked' : '' }}>
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-save mr-1"></i> {{ __('actions.update') }}
                    </button>
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">
                        <i class="la la-times-circle mr-1"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
