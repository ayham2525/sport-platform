@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-list text-primary mr-1"></i> {{ __('titles.program_details') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.programs.index') }}" class="text-muted">
                <i class="fas fa-list mr-1"></i> {{ __('titles.programs') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ $program->name_en }}</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom shadow-sm mb-5">
        <div class="card-header">
            <h3 class="card-title">{{ __('titles.program_details') }}</h3>
        </div>
        <div class="card-body">
            <p><strong>{{ __('columns.program_name') }}:</strong> {{ $program->name_en }}</p>
            <p><strong>{{ __('columns.academy') }}:</strong> {{ $program->academy->name_en ?? '-' }}</p>
            <p><strong>{{ __('columns.price') }}:</strong> {{ number_format($program->price, 2) }} {{ $program->currency }}</p>
            <p><strong>{{ __('columns.active') }}:</strong>
                @if ($program->is_active)
                    <span class="badge badge-success">{{ __('labels.active') }}</span>
                @else
                    <span class="badge badge-danger">{{ __('labels.inactive') }}</span>
                @endif
            </p>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chalkboard-teacher mr-2"></i> {{ __('titles.classes') }}
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('admin.classes.create', $program->id) }}" class="btn btn-sm btn-primary">
                    <i class="la la-plus-circle"></i> {{ __('class.actions.add_class') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($program->classes->count())
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('class.fields.day') }}</th>
                            <th>{{ __('class.fields.start_time') }}</th>
                            <th>{{ __('class.fields.end_time') }}</th>
                            <th>{{ __('class.fields.location') }}</th>
                            <th>{{ __('class.fields.coach_name') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($program->classes as $index => $class)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ __('class.days.' . strtolower($class->day)) }}</td>
                                <td>{{ $class->start_time }}</td>
                                <td>{{ $class->end_time ?? '-' }}</td>
                                <td>{{ $class->location ?? '-' }}</td>
                                <td>{{ $class->coach_name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">{{ __('class.messages.no_classes') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
