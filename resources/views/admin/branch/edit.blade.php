@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="la la-code-fork mr-1"></i> {{ __('branch.edit') }}: {{ $branch->name }}
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
            <i class="la la-code-fork mr-1"></i> {{ __('branch.title') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">
            <i class="la la-edit mr-1"></i> {{ __('branch.edit') }}
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
                    <i class="la la-edit mr-1"></i> {{ __('branch.edit') }}: {{ $branch->name }}
                </h3>
            </div>

            <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST" class="form">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    @php $inputClass = 'form-control form-control-solid'; @endphp

                    {{-- Branch Names --}}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label><i class="la la-language mr-1"></i> {{ __('branch.name_en') }}</label>
                            <input type="text" name="name" value="{{ old('name', $branch->name) }}" class="{{ $inputClass }} @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label><i class="la la-language mr-1"></i> {{ __('branch.name_ar') }}</label>
                            <input type="text" name="name_ar" value="{{ old('name_ar', $branch->name_ar) }}" class="{{ $inputClass }} @error('name_ar') is-invalid @enderror">
                            @error('name_ar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label><i class="la la-language mr-1"></i> {{ __('branch.name_ur') }}</label>
                            <input type="text" name="name_ur" value="{{ old('name_ur', $branch->name_ur) }}" class="{{ $inputClass }} @error('name_ur') is-invalid @enderror">
                            @error('name_ur') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label><i class="la la-flag mr-1"></i> {{ __('branch.country') }}</label>
                            <select id="country_id" class="{{ $inputClass }}">
                                <option value="">{{ __('branch.select_country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ old('country_id', $branch->city->state->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-map mr-1"></i> {{ __('branch.state') }}</label>
                            <select id="state_id" class="{{ $inputClass }}">
                                <option value="">{{ __('branch.select_state') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id', $branch->city->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-city mr-1"></i> {{ __('branch.city') }}</label>
                            <select name="city_id" id="city_id" class="{{ $inputClass }} @error('city_id') is-invalid @enderror">
                                <option value="">{{ __('branch.select_city') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $branch->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- System / Address / Phone --}}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label><i class="la la-cogs mr-1"></i> {{ __('branch.system') }}</label>
                            <select name="system_id" id="system_id" class="{{ $inputClass }} @error('system_id') is-invalid @enderror">
                                <option value="">{{ __('branch.select_system') }}</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}" {{ old('system_id', $branch->system_id) == $system->id ? 'selected' : '' }}>
                                        {{ $system->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('system_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-map-marker mr-1"></i> {{ __('branch.address') }}</label>
                            <input type="text" name="address" value="{{ old('address', $branch->address) }}" class="{{ $inputClass }} @error('address') is-invalid @enderror">
                            @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-phone mr-1"></i> {{ __('branch.phone') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" class="{{ $inputClass }} @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                   {{-- Status --}}
<div class="form-group row align-items-center">
    <label class="col-md-3 col-form-label">
        <i class="la la-toggle-on mr-1"></i> {{ __('branch.status') }}
    </label>
    <div class="col-md-6">
        <span class="switch switch-outline switch-icon switch-success">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-check-circle mr-1"></i> {{ __('branch.update') }}
                    </button>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">
                        <i class="la la-times-circle mr-1"></i> {{ __('branch.cancel') }}
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

    $('#system_id').select2();
    $('#city_id').select2();
    $('#state_id').select2();
    $('#country_id').select2();

    $('#country_id').change(function () {
        const countryID = $(this).val();
        $.ajax({
            url: '{{ route('admin.getStatesByCountry') }}',
            type: 'GET',
            data: { country_id: countryID },
            success: function (states) {
                let stateOptions = '<option value="">-- Select State --</option>';
                $.each(states, function (_, state) {
                    stateOptions += `<option value="${state.id}">${state.name}</option>`;
                });
                $('#state_id').html(stateOptions).trigger('change');
                $('#city_id').html('<option value="">-- Select City --</option>');
            }
        });
    });

    $('#state_id').change(function () {
        const stateID = $(this).val();
        $.ajax({
            url: '{{ route('admin.getCitiesByState') }}',
            type: 'GET',
            data: { state_id: stateID },
            success: function (cities) {
                let cityOptions = '<option value="">-- Select City --</option>';
                $.each(cities, function (_, city) {
                    cityOptions += `<option value="${city.id}">${city.name}</option>`;
                });
                $('#city_id').html(cityOptions);
            }
        });
    });
});
</script>

