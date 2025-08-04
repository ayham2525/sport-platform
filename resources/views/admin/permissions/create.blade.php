@extends('layouts.app')

<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 42px;
        padding: 5px;
        overflow-y: auto;
        max-height: 120px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #f3f6f9;
        border-color: #d1d3e2;
        color: #3f4254;
        font-weight: 500;
        padding: 4px 8px;
        margin-top: 2px;
    }
</style>

@section('content')
@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.new_record') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.permissions.index') }}" class="text-muted">{{ __('titles.permissions') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('titles.new_record') }}</span>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">{{ __('titles.new_record') }}</h3>
            </div>
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-row">
                        {{-- System --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>{{ __('columns.system') }} <span class="text-danger">*</span></label>
                            <select name="system_id" id="system_id" class="form-control @error('system_id') is-invalid @enderror">
                                <option value="">{{ __('columns.select_system') }}</option>
                                @foreach ($systems as $system)
                                <option value="{{ $system->id }}" {{ old('system_id') == $system->id ? 'selected' : '' }}>
                                    {{ $system->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('system_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Role (depends on system) --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>{{ __('columns.role') }} <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                <option value="">{{ __('columns.select_role') }}</option>
                                {{-- Will be dynamically populated --}}
                            </select>
                            @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Model --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>{{ __('columns.model') }} <span class="text-danger">*</span></label>
                            <select name="model_id" class="form-control @error('model_id') is-invalid @enderror">
                                <option value="">{{ __('columns.select_model') }}</option>
                                @foreach ($models as $model)
                                <option value="{{ $model->id }}" {{ old('model_id') == $model->id ? 'selected' : '' }}>
                                    {{ $model->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('model_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Actions (multi-select) --}}
                        <div class="form-group col-md-6 col-sm-12">
                            <label>
                                {{ __('columns.action') }} <span class="text-danger">*</span>
                                <div class="float-right">
                                    <input type="checkbox" id="select-all-actions"> {{ __('actions.select_all') }}
                                </div>
                            </label>
                            <select name="action[]" id="action-select" class="form-control @error('action') is-invalid @enderror" multiple>
                                @foreach ($actions as $action)
                                @php
                                    $icons = [
                                        'view' => 'la la-eye',
                                        'create' => 'la la-plus-circle',
                                        'update' => 'la la-edit',
                                        'delete' => 'la la-trash',
                                        'export' => 'la la-file-export',
                                        'download' => 'la la-download',
                                    ];
                                    $icon = $icons[$action] ?? 'la la-check';
                                @endphp
                                <option value="{{ $action }}" data-icon="{{ $icon }}" {{ collect(old('action'))->contains($action) ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                                @endforeach
                            </select>
                            @error('action')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="la la-check"></i> {{ __('actions.save') }}
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                        <i class="la la-arrow-left"></i> {{ __('actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Multi-select with icons
    function formatOptionWithIcon(option) {
        if (!option.id) return option.text;
        const icon = $(option.element).data('icon');
        return $(`<span><i class="${icon} mr-2"></i>${option.text}</span>`);
    }

    const $actionSelect = $('#action-select');
    $actionSelect.select2({
        theme: "default",
        closeOnSelect: false,
        allowClear: true,
        templateResult: formatOptionWithIcon,
        templateSelection: formatOptionWithIcon,
        escapeMarkup: markup => markup
    });

    // Select All / Deselect All
    document.getElementById('select-all-actions').addEventListener('change', function () {
        if (this.checked) {
            $actionSelect.find('option').prop('selected', true);
        } else {
            $actionSelect.find('option').prop('selected', false);
        }
        $actionSelect.trigger('change');
    });

    // Sync checkbox when user selects manually
    $actionSelect.on('change', function () {
        const total = $(this).find('option').length;
        const selected = $(this).val()?.length || 0;
        document.getElementById('select-all-actions').checked = (selected === total);
    });

    // Load roles dynamically when system changes
    $('#system_id').on('change', function () {
        let systemId = $(this).val();
        $('#role_id').html('<option value="">{{ __("Loading...") }}</option>');

     if (systemId) {
    $.ajax({
        url: '/admin/get-roles-by-system/' + systemId,
        type: 'GET',
        success: function (roles) {
            let options = '<option value="">{{ __("columns.select_role") }}</option>';
            $.each(roles, function (index, role) {
                options += `<option value="${role.id}">${role.name}</option>`;
            });
            $('#role_id').html(options);
        }
    });
} else {
    $('#role_id').html('<option value="">{{ __("columns.select_role") }}</option>');
}

    });
</script>
@endsection
