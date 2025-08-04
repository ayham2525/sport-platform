@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.edit_user') }}</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.users.index') }}" class="text-muted">{{ __('titles.users') }}</a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('titles.edit_user') }}</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ __('titles.edit_user') }}</h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light-primary font-weight-bolder">
                        <i class="la la-arrow-left"></i> {{ __('actions.back') }}
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

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

                    {{-- NAME / EMAIL --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label required">{{ __('columns.name') }}</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label required">{{ __('columns.email') }}</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    {{-- SYSTEM & ROLE --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">{{ __('columns.system') }}</label>
                            <select name="system_id" class="form-control" id="system_id">
                                <option value="">{{ __('messages.select_system') }}</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}"
                                        {{ old('system_id', $user->system_id) == $system->id ? 'selected' : '' }}>
                                        {{ $system->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label required">{{ __('columns.role') }}</label>
                           <select name="role" id="role" class="form-control" required>
                            <option value="">{{ __('messages.select_role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->slug }}"
                                    {{ strtolower(trim(old('role', $user->role))) === strtolower(trim($role->slug)) ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                    {{-- BRANCH (conditional) --}}
                    <div class="form-group row {{ in_array(old('role',$user->role),['branch_admin','academy_admin','coach']) ? '' : 'd-none' }}"
                         id="branch-wrapper">
                        <div class="col-md-6">
                            <label class="col-form-label">{{ __('columns.branch') }}</label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">{{ __('messages.select_branch') }}</option>
                                {{-- Filled dynamically --}}
                            </select>
                        </div>
                    </div>

                    {{-- ACADEMY (conditional) --}}
                    <div class="form-group row {{ in_array(old('role',$user->role),['academy_admin','coach']) ? '' : 'd-none' }}"
                         id="academy-wrapper">
                        <div class="col-md-6">
                            <label class="col-form-label">{{ __('columns.academy') }}</label>
                            <select name="academy_id[]" id="academy_id" class="form-control" multiple>
                                {{-- Filled dynamically --}}
                            </select>
                        </div>
                    </div>

                    {{-- LANGUAGE & PASSWORD --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">{{ __('columns.language') }}</label>
                            <select name="language" class="form-control">
                                <option value="">{{ __('messages.select_language') }}</option>
                                <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ old('language', $user->language) == 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">{{ __('columns.password') }}</label>
                            <div class="input-group">
                                <input type="password" name="password" id="generated-password" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary"
                                            onclick="generatePassword()">{{ __('actions.generate') }}</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">{{ __('messages.leave_blank_if_not_change') }}</small>
                        </div>
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">{{ __('columns.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                </div> {{-- /card-body --}}

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="la la-check-circle"></i> {{ __('actions.save') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Laravel-translated messages (safe injection)
    const selectRoleText   = "{{ __('messages.select_role') }}";
    const selectBranchText = "{{ __('messages.select_branch') }}";

    // Password Generator
    function generatePassword(length = 10) {
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let pass = '';
        for (let i = 0; i < length; i++) pass += charset[Math.floor(Math.random() * charset.length)];
        document.getElementById('generated-password').value = pass;
    }

    // Element Selectors
    const systemSelect   = document.getElementById('system_id');
    const roleSelect     = document.getElementById('role');
    const branchWrapper  = document.getElementById('branch-wrapper');
    const branchSelect   = document.getElementById('branch_id');
    const academyWrapper = document.getElementById('academy-wrapper');
    const academySelect  = document.getElementById('academy_id');

    // Toggle Branch & Academy Fields Based on Role
    function toggleWrappers(roleVal) {
        const showBranch  = ['branch_admin','academy_admin','coach'].includes(roleVal);
        const showAcademy = ['academy_admin','coach'].includes(roleVal);

        branchWrapper.classList.toggle('d-none', !showBranch);
        academyWrapper.classList.toggle('d-none', !showAcademy);

        if (!showBranch)  branchSelect.value = '';
        if (!showAcademy) academySelect.innerHTML = '';
    }

    // Initial toggle on page load
    roleSelect.addEventListener('change', () => toggleWrappers(roleSelect.value));
    toggleWrappers(roleSelect.value);

    // Load roles & branches dynamically when system changes
    if (systemSelect) {
        systemSelect.addEventListener('change', function () {
            const systemId = this.value;

            // Clear & reload roles
            roleSelect.innerHTML = `<option value="">${selectRoleText}</option>`;
            const currentRole = '{{ old('role', $user->role) }}';

            if (systemId) {
                fetch(`/admin/get-roles-by-system/${systemId}`)
                    .then(res => res.json())
                    .then(roles => {
                        roles.forEach(r => {
                            const opt = document.createElement('option');
                            opt.value = r.slug;
                            opt.textContent = r.name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                            if (r.slug === currentRole) opt.selected = true;
                            roleSelect.appendChild(opt);
                        });
                        toggleWrappers(currentRole);
                    });
            }

            // Clear & reload branches
            branchSelect.innerHTML = `<option value="">${selectBranchText}</option>`;
            academySelect.innerHTML = '';
            if (systemId) {
                fetch(`/admin/get-branches-by-system/${systemId}`)
                    .then(res => res.json())
                    .then(branches => {
                        branches.forEach(b => {
                            const opt = document.createElement('option');
                            opt.value = b.id;
                            opt.textContent = b.name;
                            branchSelect.appendChild(opt);
                        });

                        branchSelect.value = '{{ old('branch_id', $user->branch_id) }}';
                        branchSelect.dispatchEvent(new Event('change'));
                    });
            }
        });

        // Trigger fetch on load if system is already selected
        if (systemSelect.value) systemSelect.dispatchEvent(new Event('change'));
    }

    // Load academies when branch changes
    branchSelect.addEventListener('change', function () {
        const branchId = this.value;
        academySelect.innerHTML = '';
        if (branchId) {
            fetch(`/admin/get-academies-by-branch/${branchId}`)
                .then(res => res.json())
                .then(academies => {
                    academies.forEach(a => {
                        const opt = document.createElement('option');
                        opt.value = a.id;
                        opt.textContent = a.name_en;
                        academySelect.appendChild(opt);
                    });

                    // Re-initialize Select2 if used
                    if ($(academySelect).data('select2')) {
                        $(academySelect).select2();
                    }

                    // Pre-select user's academies
                    @if ($user->academies && $user->academies->count())
                        const userAcs = @json($user->academies->pluck('id'));
                        $(academySelect).val(userAcs).trigger('change');
                    @endif
                });
        }
    });

    // Pre-load academies if a branch is selected
    if (branchSelect.value) branchSelect.dispatchEvent(new Event('change'));
});
</script>


@endsection
