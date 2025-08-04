@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-university mr-2"></i> {{ __('academy.create_title') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="la la-home mr-1"></i> {{ __('academy.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.academies.index') }}" class="text-muted">
                <i class="la la-university mr-1"></i> {{ __('academy.title') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">
                <i class="la la-plus mr-1"></i> {{ __('academy.actions.create') }}
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
                    <i class="la la-plus-circle mr-1"></i> {{ __('academy.create_title') }}
                </h3>
            </div>

            <form action="{{ route('admin.academies.store') }}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    @php $inputClass = 'form-control form-control-solid'; @endphp

                    {{-- Academy Names --}}
                    <div class="form-row">
                        @foreach (['en', 'ar', 'ur'] as $lang)
                            <div class="form-group col-md-4 col-sm-12">
                                <label>
                                    <i class="la la-font mr-1"></i> {{ __('academy.fields.name_'.$lang) }}
                                </label>
                                <input type="text" name="name_{{ $lang }}" class="{{ $inputClass }} @error('name_'.$lang) is-invalid @enderror" value="{{ old('name_'.$lang) }}" placeholder="{{ __('academy.placeholders.name_'.$lang) }}">
                                @error('name_'.$lang) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        @endforeach
                    </div>

                    {{-- Branch --}}
                    <div class="form-group">
                        <label><i class="la la-code-branch mr-1"></i> {{ __('academy.fields.branch') }}</label>
                        <select name="branch_id" class="{{ $inputClass }} select2 @error('branch_id') is-invalid @enderror">
                            <option value="">{{ __('academy.placeholders.branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Contact Email & Phone --}}
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label><i class="la la-envelope mr-1"></i> {{ __('academy.fields.email') }}</label>
                            <input type="email" name="contact_email" class="{{ $inputClass }} @error('contact_email') is-invalid @enderror" value="{{ old('contact_email') }}" placeholder="{{ __('academy.placeholders.email') }}">
                            @error('contact_email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label><i class="la la-phone mr-1"></i> {{ __('academy.fields.phone') }}</label>
                            <input type="text" name="phone" class="{{ $inputClass }} @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="{{ __('academy.placeholders.phone') }}">
                            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Descriptions --}}
                    <div class="form-row">
                        @foreach (['en', 'ar', 'ur'] as $lang)
                            <div class="form-group col-md-4 col-sm-12">
                                <label>
                                    <i class="la la-info-circle mr-1"></i> {{ __('academy.fields.description_'.$lang) }}
                                </label>
                                <input type="text" name="description_{{ $lang }}" class="{{ $inputClass }}" value="{{ old('description_'.$lang) }}">
                            </div>
                        @endforeach
                    </div>

                    {{-- Status --}}
                    <div class="form-group row align-items-center">
                        <label class="col-lg-3 col-form-label">
                            <i class="la la-toggle-on mr-1"></i> {{ __('academy.fields.status') }}
                        </label>
                        <div class="col-lg-6">
                            <span class="switch switch-outline switch-icon switch-success">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} />
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="la la-save mr-1"></i> {{ __('academy.actions.save') }}
                    </button>
                    <a href="{{ route('admin.academies.index') }}" class="btn btn-secondary">
                        <i class="la la-times mr-1"></i> {{ __('academy.actions.cancel') }}
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>


