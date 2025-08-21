@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')
<style>
    .table-nowrap td,
    .table-nowrap th {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-nowrap td {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px; /* adjust as needed */
         font-size: 12px
    }
</style>
@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('player.titles.players') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('player.titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('player.titles.players') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('player.titles.players') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('player.titles.players_management') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                   @if (PermissionHelper::hasPermission('create', App\Models\Player::MODEL_NAME))
                    <a href="{{ route('admin.players.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i> {{ __('player.titles.new_record') }}
                    </a>
                    @endif
                    @if (PermissionHelper::hasPermission('export', App\Models\Player::MODEL_NAME))
                        <a href="{{ route('admin.players.export') }}" class="btn btn-success font-weight-bolder ml-2">
                            <i class="la la-file-excel"></i> {{ __('player.actions.export_excel') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

               <form method="GET" action="{{ route('admin.players.index') }}" class="mb-5">
    <div class="form-row align-items-end">
        <div class="form-group col-md-3">
            <label>{{ __('player.fields.system') }}</label>
            <select id="system_id" name="system_id" class="form-control select2">
                <option value="">{{ __('player.actions.select') }}</option>
                @foreach ($systems as $system)
                    <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>
                        {{ $system->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-3">
            <label>{{ __('player.fields.branch') }}</label>
            <select id="branch_id" name="branch_id" class="form-control select2">
                <option value="">{{ __('player.actions.select') }}</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-3">
            <label>{{ __('player.fields.academy') }}</label>
            <select id="academy_id" name="academy_id" class="form-control select2">
                <option value="">{{ __('player.actions.select') }}</option>
                @foreach ($academies as $academy)
                    <option value="{{ $academy->id }}" {{ request('academy_id') == $academy->id ? 'selected' : '' }}>
                        {{ $academy->name_en }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-3">
            <label>{{ __('player.fields.sport') }}</label>
            <select id="sport_id" name="sport_id" class="form-control select2">
                <option value="">{{ __('player.actions.select') }}</option>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                        {{ app()->getLocale() === 'ar' ? $sport->name_ar : $sport->name_en }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-3">
            <label>{{ __('player.fields.player_code') }}</label>
            <input type="text" name="search" class="form-control" placeholder="{{ __('player.fields.player_code') }}" value="{{ request('search') }}">
        </div>

        <!-- زر الفلترة -->
        <div class="form-group col-md-2">
            <button type="submit" class="btn btn-info btn-block">
                <i class="la la-filter"></i> {{ __('player.actions.filter') }}
            </button>
        </div>
        <div class="form-group col-md-2">
    <a href="{{ route('admin.players.index') }}" class="btn btn-secondary btn-block">
        <i class="la la-undo"></i> {{ __('player.actions.reset') }}
    </a>
</div>
    </div>
</form>


                <div id="players-table-wrapper">
                    @include('admin.player.partials.table', ['players' => $players])
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 1. Assign Program Modal -->
<!-- Assign Program Modal -->

@include('admin.player.partials.assignProgram');



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function () {
        $('.select2').select2();
    });

(function () {
    const $system  = $('#system_id');
    const $branch  = $('#branch_id');
    const $academy = $('#academy_id');

    const placeholderText = @json(__('player.actions.select'));

    // Initialize Select2 if present
    if (typeof $.fn.select2 === 'function') {
        $('.select2').select2({ placeholder: placeholderText, allowClear: true });
    }

    // Named route templates (inside admin group => final name is "admin.getAcademiesByBranch")
    const academiesByBranchUrlTpl = @json(route('admin.getAcademiesByBranch', ['branch_id' => '__ID__']));
    // (Optional) if you also cascade System -> Branch, keep this:
    const branchesBySystemUrlTpl  = @json(route('admin.getBranchesBySystem', ['system_id' => '__ID__']));

    // Helpers
    function resetSelect($el) {
        $el.empty().append(new Option(placeholderText, ''));
        if ($el.hasClass('select2')) $el.trigger('change.select2');
        $el.prop('disabled', false);
    }

    function setLoading($el) {
        $el.empty().append(new Option(placeholderText, ''));
        $el.append(new Option('Loading...', '', true, true));
        if ($el.hasClass('select2')) $el.trigger('change.select2');
        $el.prop('disabled', true);
    }

    function populateSelect($el, items, textKey, selectedValue = '') {
        $el.empty().append(new Option(placeholderText, ''));
        items.forEach(i => $el.append(new Option(i[textKey], i.id)));
        if (selectedValue) $el.val(String(selectedValue));
        if ($el.hasClass('select2')) $el.trigger('change.select2');
        $el.prop('disabled', false);
    }

    // --- MAIN: Load academies for a given branch ---
    function loadAcademiesByBranch(branchId, preselect = '') {
        if (!branchId) { resetSelect($academy); return; }
        setLoading($academy);
        const url = academiesByBranchUrlTpl.replace('__ID__', branchId);
        fetch(url)
            .then(r => r.json())
            .then(list => {
                // Your API returns [{ id, name_en }]
                populateSelect($academy, list, 'name_en', preselect);
            })
            .catch(() => {
                resetSelect($academy);
                console.error('Failed to load academies for branch', branchId);
            });
    }

    // (Optional) If you also want System -> Branch cascade
    function loadBranchesBySystem(systemId, preselect = '') {
        if (!systemId) { resetSelect($branch); resetSelect($academy); return Promise.resolve(); }
        setLoading($branch);
        resetSelect($academy);
        const url = branchesBySystemUrlTpl.replace('__ID__', systemId);
        return fetch(url)
            .then(r => r.json())
            .then(list => {
                // Expecting [{ id, name }]
                populateSelect($branch, list, 'name', preselect);
            })
            .catch(() => {
                resetSelect($branch);
                console.error('Failed to load branches for system', systemId);
            });
    }

    // Events
    $branch.on('change', function () {
        loadAcademiesByBranch(this.value);
    });

    // If you also handle system -> branch
    $system.on('change', function () {
        loadBranchesBySystem(this.value);
    });

    // Rehydrate on initial load (preserve filters on refresh/back)
    const initialBranch  = $branch.val();
    const initialAcademy = @json((string) request('academy_id'));
    if (initialBranch) {
        loadAcademiesByBranch(initialBranch, initialAcademy);
    }

    // Optional: expose your code generator if you use it
    window.generatePlayerCode = function () {
        const prefix = 'PLY-';
        const random = Math.floor(Math.random() * 900000 + 100000);
        const el = document.getElementById('player_code');
        if (el) el.value = `${prefix}${random}`;
    };
})();

$(document).on('click', '.delete-button', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');
    let playerId = form.data('id');

    Swal.fire({
        title: "{{ __('messages.confirm_delete') }}",
        text: "{{ __('messages.delete_warning') }}",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "{{ __('messages.yes_delete') }}",
        cancelButtonText: "{{ __('messages.cancel') }}",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: "{{ __('messages.deleted') }}",
                        text: "{{ __('player.messages.player_deleted_successfully') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // remove row dynamically
                    form.closest('tr').fadeOut(500, function() {
                        $(this).remove();
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: "{{ __('messages.error') }}",
                        text: xhr.responseJSON?.message || "{{ __('messages.something_went_wrong') }}",
                    });
                }
            });
        }
    });
});
</script>


@endsection

