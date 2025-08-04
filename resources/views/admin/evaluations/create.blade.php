@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.create_evaluation') }}</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.evaluations.index') }}" class="text-muted">{{ __('titles.evaluations') }}</a></li>
        <li class="breadcrumb-item"><span class="text-muted">{{ __('titles.create_evaluation') }}</span></li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">{{ __('titles.create_evaluation') }}</h3>
        </div>

        <form action="{{ route('admin.evaluations.store') }}" method="POST">
            @csrf
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>{{ __('columns.system') }} <span class="text-danger">*</span></label>
                    <select name="system_id" class="form-control" required>
                        <option value="">{{ __('messages.select_system') }}</option>
                        @foreach ($systems as $system)
                            <option value="{{ $system->id }}" {{ old('system_id') == $system->id ? 'selected' : '' }}>
                                {{ $system->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ __('columns.title') }} <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label>{{ __('columns.description') }}</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>{{ __('columns.type') }} <span class="text-danger">*</span></label>
                    <select name="type" class="form-control" id="type-select" required>
                        <option value="">{{ __('messages.select_type') }}</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="internal" {{ old('type') == 'internal' ? 'selected' : '' }}>Internal</option>
                        <option value="student" {{ old('type') == 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>

                <div class="form-group date-range">
                    <label>{{ __('columns.start_date') }}</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                </div>

                <div class="form-group date-range">
                    <label>{{ __('columns.end_date') }}</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>

                <div class="form-group">
                    <label>{{ __('columns.active') }}</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>{{ __('No') }}</option>
                    </select>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success">{{ __('actions.save') }}</button>
                <a href="{{ route('admin.evaluations.index') }}" class="btn btn-secondary">{{ __('actions.cancel') }}</a>
            </div>
        </form>
    </div>
</div>

<script>
    const typeSelect = document.getElementById('type-select');
    const dateRangeFields = document.querySelectorAll('.date-range');

    function toggleDateFields() {
        const type = typeSelect.value;
        dateRangeFields.forEach(field => {
            field.style.display = (type === 'internal' || type === 'student') ? 'block' : 'none';
        });
    }

    typeSelect.addEventListener('change', toggleDateFields);
    toggleDateFields(); // on load
</script>
@endsection
