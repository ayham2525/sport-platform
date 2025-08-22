@php
    $readonlyRoles = ['academy_admin', 'coach', 'player'];
    $isDisabled = in_array(auth()->user()->role, $readonlyRoles);
    $selectedSystemId = old('system_id', $player->user->system_id ?? null);
    $selectedBranchId = old('branch_id', $player->user->branch_id ?? $player->branch_id ?? null);
    $rawAcademyId = old('academy_id', $player->user->academy_id ?? $player->academy_id ?? null);

    $academyIds = is_array($rawAcademyId)
        ? array_map('intval', $rawAcademyId)
        : (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')
            ? array_map('intval', json_decode($rawAcademyId, true) ?? [])
            : [(int) $rawAcademyId]);
@endphp
<div class="row">
    <div class="col-md-6">

       <!-- System -->
<div class="form-group">
    <label><i class="fas fa-cogs text-secondary mr-1"></i> {{ __('player.fields.system') }}</label>

    @if($isDisabled)
        <input type="hidden" name="system_id" value="{{ $selectedSystemId }}">
    @endif


    <select name="system_id" id="system_id" class="form-control" @if(auth()->user()->role != 'full_admin') disabled @endif required>
        <option value="">{{ __('player.actions.select') }}</option>
        @foreach($systems as $system)
            <option value="{{ $system->id }}" {{ $selectedSystemId == $system->id ? 'selected' : '' }} >
                {{ $system->name }}
            </option>
        @endforeach
    </select>
</div>


        <!-- Name -->
        <div class="form-group">
            <label>
                <i class="fas fa-user text-secondary mr-1"></i>
                {{ __('player.fields.full_name') }} <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $player->user->name ?? '') }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>
                <i class="fas fa-envelope text-secondary mr-1"></i>
                {{ __('player.fields.email') }} <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $player->user->email ?? '') }}" required>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label>
                <i class="fas fa-lock text-secondary mr-1"></i>
                {{ __('player.fields.password') }}
                <small class="text-muted">({{ __('player.hints.leave_blank_to_keep') }})</small>
            </label>
            <input type="password" name="password" class="form-control">
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label>
                <i class="fas fa-lock text-secondary mr-1"></i>
                {{ __('player.fields.confirm_password') }}
            </label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>


        <!-- Player Code -->
        <div class="form-group">
            <label>
                <i class="fas fa-id-badge text-secondary mr-1"></i>
                {{ __('player.fields.player_code') }}
            </label>
            <div class="input-group">
                <input type="text" readonly id="player_code" name="player_code" class="form-control" value="{{ old('player_code', $player->player_code) }}">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" onclick="generatePlayerCode()">
                        <i class="fas fa-magic"></i> {{ __('player.actions.generate') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Guardian Name -->
        <div class="form-group">
            <label>
                <i class="fas fa-user-friends text-secondary mr-1"></i>
                {{ __('player.fields.guardian_name') }}
            </label>
            <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $player->guardian_name) }}">
        </div>

        <!-- Guardian Phone -->
        <div class="form-group">
            <label>
                <i class="fas fa-phone text-secondary mr-1"></i>
                {{ __('player.fields.guardian_phone') }}
            </label>
            <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $player->guardian_phone) }}">
        </div>

        <!-- Position -->
        <div class="form-group">
            <label><i class="fas fa-running text-secondary mr-1"></i> {{ __('player.fields.position') }}</label>
            <select name="position" class="form-control">
                <option value="">{{ __('player.actions.select') }}</option>

                <!-- Football (Soccer) -->
                <optgroup label="{{ __('player.positions_groups.football') }}">
                    <option value="Goalkeeper" {{ old('position', $player->position) == 'Goalkeeper' ? 'selected' : ''
                        }}>{{ __('player.positions.goalkeeper') }}</option>
                    <option value="Defender" {{ old('position', $player->position) == 'Defender' ? 'selected' : '' }}>{{
                        __('player.positions.defender') }}</option>
                    <option value="Midfielder" {{ old('position', $player->position) == 'Midfielder' ? 'selected' : ''
                        }}>{{ __('player.positions.midfielder') }}</option>
                    <option value="Forward" {{ old('position', $player->position) == 'Forward' ? 'selected' : '' }}>{{
                        __('player.positions.forward') }}</option>
                </optgroup>

                <!-- Basketball -->
                <optgroup label="{{ __('player.positions_groups.basketball') }}">
                    <option value="Point Guard" {{ old('position', $player->position) == 'Point Guard' ? 'selected' : ''
                        }}>{{ __('player.positions.point_guard') }}</option>
                    <option value="Shooting Guard" {{ old('position', $player->position) == 'Shooting Guard' ?
                        'selected' : '' }}>{{ __('player.positions.shooting_guard') }}</option>
                    <option value="Small Forward" {{ old('position', $player->position) == 'Small Forward' ? 'selected'
                        : '' }}>{{ __('player.positions.small_forward') }}</option>
                    <option value="Power Forward" {{ old('position', $player->position) == 'Power Forward' ? 'selected'
                        : '' }}>{{ __('player.positions.power_forward') }}</option>
                    <option value="Center" {{ old('position', $player->position) == 'Center' ? 'selected' : '' }}>{{
                        __('player.positions.center') }}</option>
                </optgroup>

                <!-- Volleyball -->
                <optgroup label="{{ __('player.positions_groups.volleyball') }}">
                    <option value="Outside Hitter" {{ old('position', $player->position) == 'Outside Hitter' ?
                        'selected' : '' }}>{{ __('player.positions.outside_hitter') }}</option>
                    <option value="Middle Blocker" {{ old('position', $player->position) == 'Middle Blocker' ?
                        'selected' : '' }}>{{ __('player.positions.middle_blocker') }}</option>
                    <option value="Setter" {{ old('position', $player->position) == 'Setter' ? 'selected' : '' }}>{{
                        __('player.positions.setter') }}</option>
                    <option value="Opposite Hitter" {{ old('position', $player->position) == 'Opposite Hitter' ?
                        'selected' : '' }}>{{ __('player.positions.opposite_hitter') }}</option>
                    <option value="Libero" {{ old('position', $player->position) == 'Libero' ? 'selected' : '' }}>{{
                        __('player.positions.libero') }}</option>
                </optgroup>

                <!-- Other -->
                <optgroup label="{{ __('player.positions_groups.other') }}">
                    <option value="All-rounder" {{ old('position', $player->position) == 'All-rounder' ? 'selected' : ''
                        }}>{{ __('player.positions.all_rounder') }}</option>
                    <option value="Utility" {{ old('position', $player->position) == 'Utility' ? 'selected' : '' }}>{{
                        __('player.positions.utility') }}</option>
                </optgroup>
            </select>
        </div>



        <!-- Level -->
        <div class="form-group">
            <label><i class="fas fa-layer-group text-secondary mr-1"></i> {{ __('player.fields.level') }}</label>
            <select name="level" class="form-control">
                <option value="">{{ __('player.actions.select') }}</option>
                <option value="beginner" {{ old('level', $player->level) == 'beginner' ? 'selected' : '' }}>{{
                    __('player.levels.beginner') }}</option>
                <option value="intermediate" {{ old('level', $player->level) == 'intermediate' ? 'selected' : '' }}>{{
                    __('player.levels.intermediate') }}</option>
                <option value="advanced" {{ old('level', $player->level) == 'advanced' ? 'selected' : '' }}>{{
                    __('player.levels.advanced') }}</option>
            </select>
        </div>


        <!-- Previous School -->
        <div class="form-group">
            <label><i class="fas fa-school text-secondary mr-1"></i> {{ __('player.fields.previous_school') }}</label>
            <input type="text" name="previous_school" class="form-control" maxlength="100" value="{{ old('previous_school', $player->previous_school) }}" placeholder="{{ __('player.placeholders.previous_school') }}">
        </div>

        <!-- Previous Academy -->
        <div class="form-group">
            <label><i class="fas fa-university text-secondary mr-1"></i> {{ __('player.fields.previous_academy')
                }}</label>
            <input type="text" name="previous_academy" class="form-control" maxlength="100" value="{{ old('previous_academy', $player->previous_academy) }}" placeholder="{{ __('player.placeholders.previous_academy') }}">
        </div>


        <!-- Player Status -->
<div class="form-group">
    <label>
        <i class="fas fa-id-card-alt text-secondary mr-1"></i>
        {{ __('player.fields.player_status') }}
    </label>
    <select name="status" class="form-control" required>
        @php $ps = old('status', $player->status ?? 'active'); @endphp
        <option value="active"  {{ $ps === 'active'  ? 'selected' : '' }}>{{ __('player.status.active') }}</option>
        <option value="expired" {{ $ps === 'expired' ? 'selected' : '' }}>{{ __('player.status.expired') }}</option>
        <option value="stopped" {{ $ps === 'stopped' ? 'selected' : '' }}>{{ __('player.status.stopped') }}</option>
    </select>
</div>

    </div>

    <div class="col-md-6">

<!-- Branch -->
<div class="form-group">
    <label><i class="fas fa-code-branch text-secondary mr-1"></i> {{ __('player.fields.branch') }}</label>

    @if($isDisabled)
        <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
    @endif

    <select name="branch_id" id="branch_id" class="form-control" {{ $isDisabled ? 'disabled' : '' }} required>
        <option value="">{{ __('player.actions.select') }}</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>
</div>

<!-- Academy -->
<div class="form-group">
    <label><i class="fas fa-graduation-cap text-secondary mr-1"></i> {{ __('player.fields.academy') }}</label>

    @if($isDisabled)
        @foreach($academyIds as $id)
            <input type="hidden" name="academy_id[]" value="{{ $id }}">
        @endforeach
    @endif

    <select name="academy_id" id="academy_id" class="form-control" {{ $isDisabled ? 'disabled' : '' }} required>
        <option value="">{{ __('player.actions.select') }}</option>
        @foreach($academies as $academy)
            <option value="{{ $academy->id }}" {{ in_array($academy->id, $academyIds) ? 'selected' : '' }}>
                {{ $academy->name_en }}
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
                <option value="{{ $nat->id }}" {{ old('nationality_id', $player->nationality_id) == $nat->id ?
                    'selected' : '' }}>
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
                <option value="{{ $sport->id }}" {{ old('sport_id', $player->sport_id) == $sport->id ? 'selected' : ''
                    }}>
                    {{ app()->getLocale() === 'ar' ? $sport->name_ar : $sport->name_en }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Birth Date -->
        <div class="form-group">
            <label><i class="fas fa-calendar-alt text-secondary mr-1"></i> {{ __('player.fields.birth_date') }}</label>
            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $player->birth_date) }}">
        </div>

        <!-- Gender -->
        <div class="form-group">
            <label><i class="fas fa-venus-mars text-secondary mr-1"></i> {{ __('player.fields.gender') }}</label>
            <select name="gender" class="form-control">
                <option value="">{{ __('player.actions.select') }}</option>
                <option value="male" {{ old('gender', $player->gender) == 'male' ? 'selected' : '' }}>{{
                    __('player.fields.male') }}</option>
                <option value="female" {{ old('gender', $player->gender) == 'female' ? 'selected' : '' }}>{{
                    __('player.fields.female') }}</option>
            </select>
        </div>


        <!-- Shirt Size -->
        <div class="form-group">
            <label><i class="fas fa-tshirt text-secondary mr-1"></i> {{ __('player.fields.shirt_size') }}</label>
            <select name="shirt_size" class="form-control">
                <option value="">{{ __('player.actions.select') }}</option>
                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                <option value="{{ $size }}" {{ old('shirt_size', $player->shirt_size) == $size ? 'selected' : '' }}>{{
                    $size }}</option>
                @endforeach
            </select>
        </div>

        <!-- Shorts Size -->
        <div class="form-group">
            <label><i class="fas fa-compress-alt text-secondary mr-1"></i> {{ __('player.fields.shorts_size') }}</label>
            <select name="shorts_size" class="form-control">
                <option value="">{{ __('player.actions.select') }}</option>
                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                <option value="{{ $size }}" {{ old('shorts_size', $player->shorts_size) == $size ? 'selected' : '' }}>{{
                    $size }}</option>
                @endforeach
            </select>
        </div>

        <!-- Shoe Size -->
        <div class="form-group">
            <label><i class="fas fa-shoe-prints text-secondary mr-1"></i> {{ __('player.fields.shoe_size') }}</label>
            <select name="shoe_size" class="form-control">
                <option value="">{{ __('player.actions.select') }}</option>
                @for ($i = 30; $i <= 50; $i++) <option value="{{ $i }}" {{ old('shoe_size', $player->shoe_size) == $i ?
                    'selected' : '' }}>{{ $i }}</option>
                    @endfor
            </select>
        </div>

        <!-- Medical Notes -->
        <div class="form-group">
            <label><i class="fas fa-notes-medical text-secondary mr-1"></i> {{ __('player.fields.medical_notes')
                }}</label>
            <textarea name="medical_notes" class="form-control" rows="2">{{ old('medical_notes', $player->medical_notes) }}</textarea>
        </div>

        <!-- Remarks -->
        <div class="form-group">
            <label><i class="fas fa-sticky-note text-secondary mr-1"></i> {{ __('player.fields.remarks') }}</label>
            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $player->remarks) }}</textarea>
        </div>

    </div>
</div>
