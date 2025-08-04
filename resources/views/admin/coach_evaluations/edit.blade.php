@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-user-edit text-primary mr-1"></i> {{ __('titles.edit_coach_evaluation') }}
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
            <a href="{{ route('admin.evaluations.index') }}" class="text-muted">
                <i class="fas fa-star-half-alt mr-1"></i> {{ __('titles.evaluations') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">
                <i class="fas fa-edit mr-1"></i> {{ __('titles.edit_coach_evaluation') }}
            </span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom shadow-sm">
        <div class="card-header bg-light">
            <h3 class="card-title">
                <i class="fas fa-user-check text-success mr-2"></i>
                {{ __('titles.edit_coach_evaluation') }}
            </h3>
        </div>
        <div class="card-body">
            @if($evaluation->start_date || $evaluation->end_date)
            <div class="alert alert-info d-flex align-items-center justify-content-between mb-4 py-3">
                <div>
                    @if($evaluation->start_date)
                        <strong><i class="fas fa-calendar-alt mr-1"></i> {{ __('columns.start_date') }}:</strong>
                        {{ \Carbon\Carbon::parse($evaluation->start_date)->format('Y-m-d') }}
                    @endif

                    @if($evaluation->end_date)
                        <span class="ml-4">
                            <strong><i class="fas fa-calendar-check mr-1"></i> {{ __('columns.end_date') }}:</strong>
                            {{ \Carbon\Carbon::parse($evaluation->end_date)->format('Y-m-d') }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
            <form action="{{ route('admin.coach_evaluation.update', $coachEvaluation->id) }}" method="POST">
                @csrf
                <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="font-weight-bold">
                            <i class="fas fa-chalkboard-teacher mr-1 text-primary"></i> {{ __('columns.coach') }}
                        </label>
                        <select name="coach_id" class="form-control" required>
                            @foreach ($coaches as $coach)
                                <option value="{{ $coach->id }}" {{ $coach->id == $coachEvaluation->coach_id ? 'selected' : '' }}>
                                    {{ $coach->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    @foreach ($evaluation->criteria->sortBy('order') as $criterion)
                        @php $value = $responses[$criterion->id] ?? ''; @endphp
                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">
                                <i class="fas fa-check-circle text-info mr-1"></i> {{ $criterion->label }}
                            </label>

                            @if ($criterion->input_type === 'rating')
                                <div class="d-flex align-items-center gap-2">
                                    <input 
                                        type="range" 
                                        name="responses[{{ $criterion->id }}]" 
                                        class="form-control-range w-100 rating-slider" 
                                        min="1" max="5" step="1" 
                                        value="{{ $value }}" required 
                                        data-output="slider-value-{{ $criterion->id }}"
                                    >
                                    <span class="ml-2" id="slider-value-{{ $criterion->id }}">{{ $value }}</span>/5
                                </div>
                            @elseif ($criterion->input_type === 'text')
                                <textarea name="responses[{{ $criterion->id }}]" class="form-control" required>{{ $value }}</textarea>
                            @elseif ($criterion->input_type === 'yesno')
                                <select name="responses[{{ $criterion->id }}]" class="form-control" required>
                                    <option value="yes" {{ $value == 'yes' ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    <option value="no" {{ $value == 'no' ? 'selected' : '' }}>{{ __('No') }}</option>
                                </select>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> {{ __('actions.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.rating-slider').forEach(slider => {
            const outputId = slider.getAttribute('data-output');
            const output = document.getElementById(outputId);
            slider.addEventListener('input', () => {
                output.textContent = slider.value;
            });
        });
    });
</script>
@endsection
