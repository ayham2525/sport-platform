@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-code-fork mr-1"></i> {{ __('branch.create_title') }}
    </h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-dashboard mr-1"></i> {{ __('branch.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.branches.index') }}" class="text-muted">
            <i class="la la-code-fork mr-1"></i> {{ __('branch.index_title') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">
            <i class="la la-plus mr-1"></i> {{ __('branch.create_title') }}
        </span>
    </li>
</ul>

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="la la-plus-circle mr-1"></i> {{ __('branch.create_title') }}
                </h3>
            </div>

            <form action="{{ route('admin.branches.store') }}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    @php $inputClass = 'form-control form-control-solid'; @endphp

                    {{-- Branch Names --}}
                    <div class="form-row">
                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-language mr-1"></i> {{ __('branch.name_en') }}</label>
                            <input type="text" name="name" class="{{ $inputClass }} @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="{{ __('branch.name_en_placeholder') }}">
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-language mr-1"></i> {{ __('branch.name_ar') }}</label>
                            <input type="text" name="name_ar" class="{{ $inputClass }} @error('name_ar') is-invalid @enderror"
                                   value="{{ old('name_ar') }}" placeholder="{{ __('branch.name_ar_placeholder') }}">
                            @error('name_ar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-language mr-1"></i> {{ __('branch.name_ur') }}</label>
                            <input type="text" name="name_ur" class="{{ $inputClass }} @error('name_ur') is-invalid @enderror"
                                   value="{{ old('name_ur') }}" placeholder="{{ __('branch.name_ur_placeholder') }}">
                            @error('name_ur') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="form-row">
                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-flag mr-1"></i> {{ __('branch.country') }}</label>
                            <select id="country_id" class="{{ $inputClass }}">
                                <option value="">{{ __('branch.select_country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-map mr-1"></i> {{ __('branch.state') }}</label>
                            <select id="state_id" class="{{ $inputClass }}">
                                <option value="">{{ __('branch.select_state') }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-city mr-1"></i> {{ __('branch.city') }}</label>
                            <select name="city_id" id="city_id" class="{{ $inputClass }} @error('city_id') is-invalid @enderror">
                                <option value="">{{ __('branch.select_city') }}</option>
                            </select>
                            @error('city_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- System / Address / Phone --}}
                    <div class="form-row">
                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-cogs mr-1"></i> {{ __('branch.system') }}</label>
                            <select name="system_id" id="system_id" class="{{ $inputClass }} @error('system_id') is-invalid @enderror">
                                <option value="">{{ __('branch.select_system') }}</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}" {{ old('system_id') == $system->id ? 'selected' : '' }}>
                                        {{ $system->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('system_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-map-marker mr-1"></i> {{ __('branch.address') }}</label>
                            <input type="text" name="address" class="{{ $inputClass }} @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" placeholder="{{ __('branch.address_placeholder') }}">
                            @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4 col-sm-12">
                            <label><i class="la la-phone mr-1"></i> {{ __('branch.phone') }}</label>
                            <input type="text" name="phone" class="{{ $inputClass }} @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" placeholder="{{ __('branch.phone_placeholder') }}">
                            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                   <div class="form-group row align-items-center">
    {{-- Status --}}
    <label class="col-md-3 col-form-label">
        <i class="la la-toggle-on mr-1"></i> {{ __('branch.status') }}
    </label>
    <div class="col-md-6">
        <span class="switch switch-outline switch-icon switch-success">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} />
                <span></span>
            </label>
        </span>
    </div>
</div>

{{-- Maximum Player Number --}}
<div class="form-group row align-items-center">
    <label class="col-md-3 col-form-label">
        <i class="la la-users mr-1"></i> {{ __('branch.maximum_player_number') }}
    </label>
    <div class="col-md-6">
        <input type="number" name="maximum_player_number"
               class="form-control form-control-solid @error('maximum_player_number') is-invalid @enderror"
               value="{{ old('maximum_player_number', $branch->maximum_player_number ?? '') }}"
               placeholder="{{ __('branch.maximum_player_number_placeholder') }}">
        @error('maximum_player_number')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="la la-check-circle mr-1"></i> {{ __('actions.save') }}
                    </button>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">
                        <i class="la la-times-circle mr-1"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    $('#country_id').select2();
    $('#city_id').select2();
    $('#state_id').select2();
    $('#system_id').select2();

    $('#country_id').change(function () {
        let countryID = $(this).val();
        $.ajax({
            url: '{{ route('admin.getStatesByCountry') }}',
            type: 'GET',
            data: { country_id: countryID },
            success: function (data) {
                let stateOptions = '<option value="">-- Select State --</option>';
                $.each(data, function (i, state) {
                    stateOptions += `<option value="${state.id}">${state.name}</option>`;
                });
                $('#state_id').html(stateOptions);
                $('#city_id').html('<option value="">-- Select City --</option>'); // reset cities
            }
        });
    });

    $('#state_id').change(function () {
        let stateID = $(this).val();
        $.ajax({
            url: '{{ route('admin.getCitiesByState') }}',
            type: 'GET',
            data: { state_id: stateID },
            success: function (data) {
                let cityOptions = '<option value="">-- Select City --</option>';
                $.each(data, function (i, city) {
                    cityOptions += `<option value="${city.id}">${city.name}</option>`;
                });
                $('#city_id').html(cityOptions);
            }
        });
    });
});
</script>

@endsection
