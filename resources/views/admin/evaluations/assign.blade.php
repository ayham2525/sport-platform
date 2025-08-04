@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.evaluate_coach') }}</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.evaluations.index') }}" class="text-muted">{{ __('titles.evaluations') }}</a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('titles.evaluate_coach') }}</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">{{ __('titles.evaluate_coach') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.coach_evaluation.store') }}" method="POST">
                @csrf
                <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="font-weight-bold">{{ __('columns.coach') }}</label>
                        <select name="coach_id" class="form-control" required>
                            @foreach ($coaches as $coach)
                                <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    @foreach ($evaluation->criteria->sortBy('order') as $criterion)
                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ $criterion->label }}</label>

                            @if ($criterion->input_type === 'rating')
                                <div class="d-flex align-items-center gap-2">
                                    <input 
                                        type="range" 
                                        name="responses[{{ $criterion->id }}]" 
                                        class="form-control-range w-100 rating-slider" 
                                        min="1" max="5" step="1" 
                                        value="3" required 
                                        data-output="slider-value-{{ $criterion->id }}"
                                    >
                                    <span class="ml-2" id="slider-value-{{ $criterion->id }}">3</span>/5
                                </div>
                            @elseif ($criterion->input_type === 'text')
                                <textarea name="responses[{{ $criterion->id }}]" class="form-control" required></textarea>
                            @elseif ($criterion->input_type === 'yesno')
                                <select name="responses[{{ $criterion->id }}]" class="form-control" required>
                                    <option value="yes">{{ __('Yes') }}</option>
                                    <option value="no">{{ __('No') }}</option>
                                </select>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('actions.submit_evaluation') }}</button>
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
