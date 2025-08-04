@extends('layouts.app')
@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.new_user') }}</h5>
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
        <span class="text-muted">{{ __('titles.new_user') }}</span>
    </li>
</ul>
@endsection
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="la la-user-plus mr-1"></i> {{ __('titles.new_user') }}
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light-primary font-weight-bolder">
                        <i class="la la-arrow-left"></i> {{ __('actions.back') }}
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
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

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label required">
                                <i class="la la-user mr-1"></i> {{ __('columns.name') }}
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label required">
                                <i class="la la-envelope mr-1"></i> {{ __('columns.email') }}
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">
                                <i class="la la-sitemap mr-1"></i> {{ __('columns.system') }}
                            </label>
                            <select name="system_id" class="form-control">
                                <option value="">{{ __('messages.select_system') }}</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}" {{ old('system_id') == $system->id ? 'selected' : '' }}>
                                        {{ $system->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label required">
                                <i class="la la-user-tag mr-1"></i> {{ __('columns.role') }}
                            </label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="">{{ __('messages.select_role') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->slug }}" {{ old('role') == $role->slug ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row d-none" id="branch-wrapper">
                        <div class="col-md-6">
                            <label class="col-form-label">
                                <i class="la la-code-branch mr-1"></i> {{ __('columns.branch') }}
                            </label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">{{ __('messages.select_branch') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row d-none" id="academy-wrapper">
                        <div class="col-md-6">
                            <label class="col-form-label">
                                <i class="la la-university mr-1"></i> {{ __('columns.academy') }}
                            </label>
                            <select name="academy_id[]" id="academy_id" class="form-control" multiple></select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">
                                <i class="la la-language mr-1"></i> {{ __('columns.language') }}
                            </label>
                            <select name="language" class="form-control">
                                <option value="">{{ __('messages.select_language') }}</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">
                                <i class="la la-key mr-1"></i> {{ __('columns.password') }}
                            </label>
                            <div class="input-group">
                                <input type="text" name="password" id="generated-password" class="form-control" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="generatePassword()">
                                        <i class="la la-random mr-1"></i> {{ __('actions.generate') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label required">
                                <i class="la la-lock mr-1"></i> {{ __('columns.confirm_password') }}
                            </label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="la la-check-circle"></i> {{ __('actions.save') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="la la-times-circle"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Password generator
    function generatePassword(length = 10) {
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        const passwordInput = document.getElementById("generated-password");
        if (passwordInput) passwordInput.value = password;
    }

    const systemSelect = document.querySelector('[name="system_id"]');
    const roleSelect = document.querySelector('[name="role"]');
    const branchWrapper = document.getElementById('branch-wrapper');
    const branchSelect = document.getElementById('branch_id');
    const academyWrapper = document.getElementById('academy-wrapper');
    const academySelect = document.getElementById('academy_id');

    // Update roles based on selected system
    if (systemSelect) {
        systemSelect.addEventListener('change', function () {
            const systemId = this.value;

            // Reset branches, academies, and roles
            if (branchSelect) branchSelect.innerHTML = `<option value="">Select branch</option>`;
            if (academySelect) academySelect.innerHTML = '';
            if (roleSelect) {
                roleSelect.innerHTML = `<option value="">Select role</option>`;
                fetch(`/admin/get-roles-by-system/${systemId}`)
                    .then(res => res.json())
                    .then(roles => {
                        roles.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role.slug;
                            option.textContent = role.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            roleSelect.appendChild(option);
                        });
                    });
            }

            // Load branches
            if (systemId && branchSelect) {
                fetch(`/admin/get-branches-by-system/${systemId}`)
                    .then(res => res.json())
                    .then(branches => {
                        branches.forEach(branch => {
                            const option = document.createElement('option');
                            option.value = branch.id;
                            option.textContent = branch.name;
                            branchSelect.appendChild(option);
                        });
                    });
            }
        });
    }

    // Load academies when branch changes
    if (branchSelect) {
        branchSelect.addEventListener('change', function () {
            const branchId = this.value;
            if (academySelect) {
                academySelect.innerHTML = '';
                if (branchId) {
                    fetch(`/admin/get-academies-by-branch/${branchId}`)
                        .then(res => res.json())
                        .then(academies => {
                            academies.forEach(ac => {
                                const option = document.createElement('option');
                                option.value = ac.id;
                                option.textContent = ac.name_en;
                                academySelect.appendChild(option);
                            });
                            // Re-init Select2 after changing options
                            if ($(academySelect).data('select2')) {
                                $(academySelect).select2();
                            }
                        });
                }
            }
        });
    }

    // Toggle branch/academy visibility based on role
    if (roleSelect) {
        roleSelect.addEventListener('change', function () {
            const role = this.value;
            const showBranch = ['branch_admin', 'academy_admin', 'coach'].includes(role);
            const showAcademy = ['academy_admin', 'coach'].includes(role);

            branchWrapper?.classList.toggle('d-none', !showBranch);
            academyWrapper?.classList.toggle('d-none', !showAcademy);

            if (!showBranch && branchSelect) branchSelect.value = '';
            if (!showAcademy && academySelect) academySelect.innerHTML = '';
        });
    }

    // Assign password generation button
    const generateBtn = document.querySelector('button[onclick="generatePassword()"]');
    if (generateBtn) {
        generateBtn.addEventListener('click', () => generatePassword());
    }
});
</script>


@endsection
