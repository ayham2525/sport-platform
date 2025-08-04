@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('systems.create_title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('systems.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.systems.index') }}" class="text-muted">{{ __('systems.title') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('systems.create') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ __('systems.add_new') }}</h3>
            </div>

            <form action="{{ route('admin.systems.store') }}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    @php
                        $inputClass = 'form-control form-control-solid';
                    @endphp

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">{{ __('systems.name') }}</label>
                        <div class="col-lg-6">
                            <input type="text" name="name"
                                   class="{{ $inputClass }} @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('systems.name_placeholder') }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">{{ __('systems.description') }}</label>
                        <div class="col-lg-6">
                            <textarea name="description"
                                      class="{{ $inputClass }} @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="{{ __('systems.description_placeholder') }}">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success mr-2">{{ __('systems.save') }}</button>
                    <a href="{{ route('admin.systems.index') }}" class="btn btn-secondary">{{ __('systems.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
