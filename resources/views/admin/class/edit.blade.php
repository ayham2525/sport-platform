@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-edit text-warning mr-1"></i> {{ __('class.titles.edit_class') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('class.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.programs.index') }}" class="text-muted">
                <i class="fas fa-list mr-1"></i> {{ __('class.titles.programs') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('class.titles.edit_class') }}</span>
        </li>
    </ul>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom shadow-sm">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-header">
                <h3 class="card-title">{{ __('class.titles.edit_class') }}</h3>
            </div>
            <div class="card-body">
<form action="{{ route('admin.classes.update', ['program' => $program->id, 'class' => $class->id]) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Day --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-day mr-1"></i> {{ __('class.fields.day') }}
                                    <span class="text-danger">*</span></label>
                                <select name="day" class="form-control" required>
                                    <option value="">{{ __('class.actions.select') }}</option>
                                    @foreach (['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                                        <option value="{{ $day }}" {{ $class->day === $day ? 'selected' : '' }}>
                                            {{ __('class.days.' . strtolower($day)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-clock  mr-1"></i> {{ __('class.fields.start_time') }}
                                    <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control"
                                       value="{{ old('start_time', $class->start_time) }}" required>
                            </div>
                        </div>

                        {{-- End Time --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="far fa-clock  mr-1"></i>
                                    {{ __('class.fields.end_time') }}</label>
                                <input type="time" name="end_time" class="form-control"
                                       value="{{ old('end_time', $class->end_time) }}">
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ __('class.fields.location') }}</label>
                                <input type="text" name="location" class="form-control"
                                       value="{{ old('location', $class->location) }}">
                            </div>
                        </div>

                        {{-- Coach --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-1"></i>
                                    {{ __('class.fields.coach_name') }}</label>
                                <select name="coach_id" class="form-control">
                                    <option value="">{{ __('class.actions.select') }}</option>
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->id }}"
                                            {{ $class->coach_id == $coach->id ? 'selected' : '' }}>
                                            {{ $coach->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right mt-4">
                        <a href="{{ route('admin.programs.show', $program->id) }}" class="btn btn-secondary">
                            {{ __('class.actions.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('class.actions.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
