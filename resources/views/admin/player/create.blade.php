@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">
    <i class="fas fa-user-plus text-success mr-1"></i> {{ __('player.titles.new_record') }}
</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="fas fa-home mr-1"></i> {{ __('player.titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.players.index') }}" class="text-muted">
            <i class="fas fa-users mr-1"></i> {{ __('player.titles.players') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('player.titles.new_record') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-plus text-success mr-2"></i> {{ __('player.titles.new_record') }}</h3>
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


            @php
            $readonlyRoles = ['system_admin','academy_admin', 'coach', 'player'];
            $isDisabled = in_array(auth()->user()->role, $readonlyRoles);

            $selectedSystemId = old('system_id', $player->user->system_id ?? auth()->user()->system_id);
            $selectedBranchId = old('branch_id', $player->user->branch_id ?? auth()->user()->branch_id);

            $rawAcademyId = old('academy_id', auth()->user()->academy_id);
            if (is_array($rawAcademyId)) {
            $academyIds = array_map('intval', $rawAcademyId);
            } elseif (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
            $academyIds = array_map('intval', json_decode($rawAcademyId, true) ?? []);
            } elseif (!is_null($rawAcademyId)) {
            $academyIds = [(int) $rawAcademyId];
            } else {
            $academyIds = [];
            }

            $nameField = 'name_' . app()->getLocale();
            @endphp

            <form method="POST" action="{{ route('admin.players.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <!-- System -->

                        {{-- System Select --}}
                        @if($isDisabled)
                        <input type="hidden" name="system_id" value="{{ $selectedSystemId }}">
                        @endif
                        <div class="form-group">
                            <label>{{ __('player.fields.system') }}</label>
                            <select name="system_id" id="system_id" class="form-control" {{ $isDisabled ? 'disabled' : '' }} required>
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach($systems as $system)
                                <option value="{{ $system->id }}" {{ (int) $selectedSystemId === (int) $system->id ? 'selected' : '' }}>
                                    {{ $system->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Name -->
                        <div class="form-group">
                            <label><i class="fas fa-user text-secondary mr-1"></i> {{ __('player.fields.full_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label><i class="fas fa-envelope text-secondary mr-1"></i> {{ __('player.fields.email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label><i class="fas fa-lock text-secondary mr-1"></i> {{ __('player.fields.password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label><i class="fas fa-lock text-secondary mr-1"></i> {{ __('player.fields.confirm_password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <!-- Player Code -->
                        <div class="form-group">
                            <label><i class="fas fa-id-badge text-secondary mr-1"></i> {{ __('player.fields.player_code') }}</label>
                            <div class="input-group">
                                <input type="text" readonly id="player_code" name="player_code" class="form-control" value="{{ old('player_code') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="generatePlayerCode()">
                                        <i class="fas fa-magic"></i> {{ __('player.actions.generate') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Name -->
                        <div class="form-group">
                            <label><i class="fas fa-user-friends text-secondary mr-1"></i> {{ __('player.fields.guardian_name') }}</label>
                            <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name') }}">
                        </div>

                        <!-- Guardian Phone -->
                        <div class="form-group">
                            <label><i class="fas fa-phone text-secondary mr-1"></i> {{ __('player.fields.guardian_phone') }}</label>
                            <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone') }}">
                        </div>

                        <!-- Position -->
                        <div class="form-group">
                            <label><i class="fas fa-running text-secondary mr-1"></i> {{ __('player.fields.position') }}</label>
                            <select name="position" class="form-control">
                                <option value="">{{ __('player.actions.select') }}</option>

                                <!-- Football (Soccer) -->
                                <optgroup label="{{ __('player.positions_groups.football') }}">
                                    <option value="Goalkeeper" {{ old('position') == 'Goalkeeper' ? 'selected' : '' }}>{{ __('player.positions.goalkeeper') }}</option>
                                    <option value="Defender" {{ old('position') == 'Defender' ? 'selected' : '' }}>{{ __('player.positions.defender') }}</option>
                                    <option value="Midfielder" {{ old('position') == 'Midfielder' ? 'selected' : '' }}>{{ __('player.positions.midfielder') }}</option>
                                    <option value="Forward" {{ old('position') == 'Forward' ? 'selected' : '' }}>{{ __('player.positions.forward') }}</option>
                                </optgroup>

                                <!-- Basketball -->
                                <optgroup label="{{ __('player.positions_groups.basketball') }}">
                                    <option value="Point Guard" {{ old('position') == 'Point Guard' ? 'selected' : '' }}>{{ __('player.positions.point_guard') }}</option>
                                    <option value="Shooting Guard" {{ old('position') == 'Shooting Guard' ? 'selected' : '' }}>{{ __('player.positions.shooting_guard') }}</option>
                                    <option value="Small Forward" {{ old('position') == 'Small Forward' ? 'selected' : '' }}>{{ __('player.positions.small_forward') }}</option>
                                    <option value="Power Forward" {{ old('position') == 'Power Forward' ? 'selected' : '' }}>{{ __('player.positions.power_forward') }}</option>
                                    <option value="Center" {{ old('position') == 'Center' ? 'selected' : '' }}>{{ __('player.positions.center') }}</option>
                                </optgroup>

                                <!-- Volleyball -->
                                <optgroup label="{{ __('player.positions_groups.volleyball') }}">
                                    <option value="Outside Hitter" {{ old('position') == 'Outside Hitter' ? 'selected' : '' }}>{{ __('player.positions.outside_hitter') }}</option>
                                    <option value="Middle Blocker" {{ old('position') == 'Middle Blocker' ? 'selected' : '' }}>{{ __('player.positions.middle_blocker') }}</option>
                                    <option value="Setter" {{ old('position') == 'Setter' ? 'selected' : '' }}>{{ __('player.positions.setter') }}</option>
                                    <option value="Opposite Hitter" {{ old('position') == 'Opposite Hitter' ? 'selected' : '' }}>{{ __('player.positions.opposite_hitter') }}</option>
                                    <option value="Libero" {{ old('position') == 'Libero' ? 'selected' : '' }}>{{ __('player.positions.libero') }}</option>
                                </optgroup>

                                <!-- Other -->
                                <optgroup label="{{ __('player.positions_groups.other') }}">
                                    <option value="All-rounder" {{ old('position') == 'All-rounder' ? 'selected' : '' }}>{{ __('player.positions.all_rounder') }}</option>
                                    <option value="Utility" {{ old('position') == 'Utility' ? 'selected' : '' }}>{{ __('player.positions.utility') }}</option>
                                </optgroup>
                            </select>
                        </div>



                        <!-- Level -->
                        <div class="form-group">
                            <label><i class="fas fa-layer-group text-secondary mr-1"></i> {{ __('player.fields.level') }}</label>
                            <select name="level" class="form-control">
                                <option value="">{{ __('player.actions.select') }}</option>
                                <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>{{ __('player.levels.beginner') }}</option>
                                <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>{{ __('player.levels.intermediate') }}</option>
                                <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>{{ __('player.levels.advanced') }}</option>
                            </select>
                        </div>


                        <!-- Previous School -->
                        <div class="form-group">
                            <label><i class="fas fa-school text-secondary mr-1"></i> {{ __('player.fields.previous_school') }}</label>
                            <input type="text" name="previous_school" class="form-control" maxlength="100" value="{{ old('previous_school') }}" placeholder="{{ __('player.placeholders.previous_school') }}">
                        </div>

                        <!-- Previous Academy -->
                        <div class="form-group">
                            <label><i class="fas fa-university text-secondary mr-1"></i> {{ __('player.fields.previous_academy') }}</label>
                            <input type="text" name="previous_academy" class="form-control" maxlength="100" value="{{ old('previous_academy') }}" placeholder="{{ __('player.placeholders.previous_academy') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Branch -->
                        {{-- Branch Select --}}
                        @if($isDisabled)
                        <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                        @endif
                        <div class="form-group">
                            <label>{{ __('player.fields.branch') }}</label>
                            <select name="branch_id" id="branch_id" class="form-control" {{ $isDisabled ? 'disabled' : '' }} required>
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (int) $selectedBranchId === (int) $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Academy Select --}}
                        @if($isDisabled)
                        @foreach($academyIds as $id)
                        <input type="hidden" name="academy_id[]" value="{{ $id }}">
                        @endforeach
                        @endif
                        <div class="form-group">
                            <label>{{ __('player.fields.academy') }}</label>
                            <select name="academy_id" id="academy_id" class="form-control" {{ $isDisabled ? 'disabled' : '' }} required>
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach($academies as $academy)
                                <option value="{{ $academy->id }}" {{ in_array((int) $academy->id, $academyIds) ? 'selected' : '' }}>
                                    {{ $academy->$nameField ?? $academy->name_en }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nationality -->
                        <div class="form-group">
                            <label><i class="fas fa-flag text-secondary mr-1"></i> {{ __('player.fields.nationality') }}</label>
                            <select name="nationality_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach($nationalities as $nat)
                                <option value="{{ $nat->id }}" {{ old('nationality_id') == $nat->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $nat->name_ar : $nat->name_en }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sport -->
                        <div class="form-group">
                            <label><i class="fas fa-futbol text-secondary mr-1"></i> {{ __('player.fields.sport') }}</label>
                            <select name="sport_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach($sports as $sport)
                                <option value="{{ $sport->id }}" {{ old('sport_id') == $sport->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $sport->name_ar : $sport->name_en }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Birth Date -->
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt text-secondary mr-1"></i> {{ __('player.fields.birth_date') }}</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                        </div>

                        <!-- Gender -->
                        <div class="form-group">
                            <label><i class="fas fa-venus-mars text-secondary mr-1"></i> {{ __('player.fields.gender') }}</label>
                            <select name="gender" class="form-control">
                                <option value="">{{ __('player.actions.select') }}</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('player.fields.male') }}</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('player.fields.female') }}</option>
                            </select>
                        </div>

                        <!-- Sizes -->
                        <div class="form-group">
                            <label><i class="fas fa-tshirt text-secondary mr-1"></i> {{ __('player.fields.shirt_size') }}</label>
                            <select name="shirt_size" class="form-control">
                                <option value="">{{ __('player.actions.select') }}</option>
                                <option value="XS" {{ old('shirt_size') == 'XS' ? 'selected' : '' }}>XS</option>
                                <option value="S" {{ old('shirt_size') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('shirt_size') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('shirt_size') == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('shirt_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('shirt_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                        </div>

                        <!-- Shorts Size -->
                        <div class="form-group">
                            <label><i class="fas fa-compress-alt text-secondary mr-1"></i> {{ __('player.fields.shorts_size') }}</label>
                            <select name="shorts_size" class="form-control">
                                <option value="">{{ __('player.actions.select') }}</option>
                                <option value="XS" {{ old('shorts_size') == 'XS' ? 'selected' : '' }}>XS</option>
                                <option value="S" {{ old('shorts_size') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('shorts_size') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('shorts_size') == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('shorts_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('shorts_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                        </div>

                        <!-- Shoe Size -->
                        <div class="form-group">
                            <label><i class="fas fa-shoe-prints text-secondary mr-1"></i> {{ __('player.fields.shoe_size') }}</label>
                            <select name="shoe_size" class="form-control">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @for ($i = 30; $i <= 50; $i++) <option value="{{ $i }}" {{ old('shoe_size') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <!-- Medical Notes -->
                        <div class="form-group">
                            <label><i class="fas fa-notes-medical text-secondary mr-1"></i> {{ __('player.fields.medical_notes') }}</label>
                            <textarea name="medical_notes" class="form-control" rows="2">{{ old('medical_notes') }}</textarea>
                        </div>

                        <!-- Remarks -->
                        <div class="form-group">
                            <label><i class="fas fa-sticky-note text-secondary mr-1"></i> {{ __('player.fields.remarks') }}</label>
                            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> {{ __('player.actions.save') }}
                    </button>
                    <a href="{{ route('admin.players.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('player.actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@php
$user = auth()->user();
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.select2').select2();
});
</script>

<script>

    function generatePlayerCode() {
        const prefix = 'PLY-';
        const random = Math.floor(Math.random() * 900000 + 100000);
        const code = `${prefix}${random}`;
        document.getElementById('player_code').value = code;
    }

    window.addEventListener('DOMContentLoaded', function() {
        $('.select2').select2({
            placeholder: "{{ __('player.actions.select') }}"
            , allowClear: true
        });

        const systemSelect = document.getElementById('system_id');
        const branchSelect = document.getElementById('branch_id');
        const academySelect = document.getElementById('academy_id');

        const selectText = {
            !!json_encode(__('player.actions.select')) !!
        };
        const selectOption = `<option value="">${selectText}</option>`;

        const getBranchesBySystemRouteTemplate = "{{ route('admin.getBranchesBySystem', ['system_id' => '__ID__']) }}";
        const getAcademiesByBranchRouteTemplate = "{{ route('admin.getAcademiesByBranch', ['branch_id' => '__ID__']) }}";

        systemSelect.addEventListener('change', function() {
            const systemId = this.value;
            if (!systemId) return;

            const url = getBranchesBySystemRouteTemplate.replace('__ID__', systemId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    branchSelect.innerHTML = selectOption;
                    data.forEach(branch => {
                        branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
                    });
                    branchSelect.dispatchEvent(new Event('change'));
                });
        });

        branchSelect.addEventListener('change', function() {
            const branchId = this.value;
            if (!branchId) return;

            const url = getAcademiesByBranchRouteTemplate.replace('__ID__', branchId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    academySelect.innerHTML = selectOption;
                    data.forEach(academy => {
                        academySelect.innerHTML += `<option value="${academy.id}">${academy.name_en}</option>`;
                    });
                });
        });
    });

</script>
