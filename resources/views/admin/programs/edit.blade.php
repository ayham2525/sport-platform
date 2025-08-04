@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.edit_program') }}</h5>
@endsection
@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="la la-home mr-1"></i> {{ __('titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.programs.index') }}" class="text-muted">
                <i class="la la-book-open mr-1"></i> {{ __('titles.programs') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">
                <i class="la la-edit mr-1"></i> {{ __('titles.edit_program') }}
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
                    <i class="la la-edit mr-1"></i> {{ __('titles.edit_program') }}
                </h3>
            </div>
            <form action="{{ route('admin.programs.update', $program->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label><i class="la la-cogs mr-1 text-muted"></i> {{ __('columns.system') }} <span class="text-danger">*</span></label>
                            <select name="system_id" id="system_id" class="form-control">
                                <option value="">{{ __('columns.select_system') }}</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}" {{ $program->system_id == $system->id ? 'selected' : '' }}>{{ $system->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-code-branch mr-1 text-muted"></i> {{ __('columns.branch') }} <span class="text-danger">*</span></label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">{{ __('columns.select_branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $program->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-university mr-1 text-muted"></i> {{ __('columns.academy') }} <span class="text-danger">*</span></label>
                            <select name="academy_id" id="academy_id" class="form-control">
                                <option value="">{{ __('columns.select_academy') }}</option>
                                @foreach ($academies as $academy)
                                    <option value="{{ $academy->id }}" {{ $program->academy_id == $academy->id ? 'selected' : '' }}>{{ $academy->name_en }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-font mr-1 text-muted"></i> {{ __('columns.name_en') }}</label>
                            <input type="text" name="name_en" class="form-control" value="{{ $program->name_en }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label><i class="la la-dollar-sign mr-1 text-muted"></i> {{ __('columns.price') }}</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $program->price }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label><i class="la la-percent mr-1 text-muted"></i> {{ __('columns.vat') }} (%)</label>
                            <input type="number" step="0.01" name="vat" class="form-control" value="{{ $program->vat }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label><i class="la la-coins mr-1 text-muted"></i> {{ __('columns.currency') }}</label>
                            <select name="currency" class="form-control">
                                <option value="AED" {{ $program->currency == 'AED' ? 'selected' : '' }}>AED</option>
                                <option value="USD" disabled {{ $program->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="SAR" disabled {{ $program->currency == 'SAR' ? 'selected' : '' }}>SAR</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label><i class="la la-list-ol mr-1 text-muted"></i> {{ __('columns.class_count') }}</label>
                            <input type="number" name="class_count" class="form-control" value="{{ $program->class_count }}">
                        </div>

                        <div class="form-group col-md-12">
                            <label><i class="la la-calendar mr-1 text-muted"></i> {{ __('columns.days') }}</label><br>
                            @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <label class="checkbox-inline mr-3">
                                    <input type="checkbox" name="days[]" value="{{ $day }}" {{ $program->days->pluck('day')->contains($day) ? 'checked' : '' }}>
                                    {{ __('days.' . strtolower($day)) }}
                                </label>
                            @endforeach
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="la la-toggle-on mr-1 text-muted"></i> {{ __('columns.status') }}</label><br>
                            <label class="radio-inline mr-3">
                                <input type="radio" name="is_active" value="1" {{ $program->is_active ? 'checked' : '' }}> {{ __('labels.active') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="is_active" value="0" {{ !$program->is_active ? 'checked' : '' }}> {{ __('labels.inactive') }}
                            </label>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-primary"><i class="la la-check"></i> {{ __('actions.save_changes') }}</button>
                    <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary"><i class="la la-arrow-left"></i> {{ __('actions.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    $('#system_id').change(function () {
        let systemId = $(this).val();
        $('#branch_id').html('<option>Loading...</option>');
        $('#academy_id').html('<option value="">{{ __('columns.select_academy') }}</option>');
        $.get(`/admin/get-branches-by-system/${systemId}`, function (branches) {
            let branchOptions = '<option value="">{{ __('columns.select_branch') }}</option>';
            branches.forEach(branch => {
                branchOptions += `<option value="${branch.id}">${branch.name}</option>`;
            });
            $('#branch_id').html(branchOptions);
        });
    });

    $('#branch_id').change(function () {
        let branchId = $(this).val();
        $('#academy_id').html('<option>Loading...</option>');
        $.get(`/admin/get-academies-by-branch/${branchId}`, function (academies) {
            let academyOptions = '<option value="">{{ __('columns.select_academy') }}</option>';
            academies.forEach(academy => {
                academyOptions += `<option value="${academy.id}">${academy.name_en}</option>`;
            });
            $('#academy_id').html(academyOptions);
        });
    });
});
</script>
