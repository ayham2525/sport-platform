@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-star-half-alt text-warning mr-1"></i> {{ __('titles.evaluations') }}
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
            <span class="text-muted">
                <i class="fas fa-star-half-alt mr-1"></i> {{ __('titles.evaluations') }}
            </span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-list-alt text-primary mr-2"></i> {{ __('titles.evaluation_list') }}
            </h3>
            <a href="{{ route('admin.evaluations.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> {{ __('actions.create') }}
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.coach_evaluation.index') }}" class="mb-4">
                <div class="form-row align-items-end">
                    <div class="col-md-3">
                        <label>{{ __('System') }}</label>
                        <select name="system_id" class="form-control">
                            <option value="">{{ __('All Systems') }}</option>
                            @foreach ($systems as $system)
                                <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>
                                    {{ $system->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>{{ __('Coach Name') }}</label>
                        <input type="text" name="coach_name" class="form-control" value="{{ request('coach_name') }}" placeholder="Min 3 characters">
                    </div>

                    <div class="col-md-2">
                        <label>{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label>{{ __('End Date') }}</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter mr-1"></i> {{ __('Filter') }}
                        </button>
                    </div>
                </div>
            </form>

            @if($coachEvaluations->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-user mr-1"></i> {{ __('columns.coach') }}</th>
                                <th><i class="fas fa-clipboard-check mr-1"></i> {{ __('columns.evaluation') }}</th>
                                <th><i class="fas fa-calendar-alt mr-1"></i> {{ __('columns.date') }}</th>
                                <th><i class="fas fa-cogs mr-1"></i> {{ __('columns.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coachEvaluations as $index => $evaluation)
                                <tr>
                                    <td>{{ $loop->iteration + ($coachEvaluations->currentPage() - 1) * $coachEvaluations->perPage() }}</td>
                                    <td>{{ $evaluation->coach->name ?? '-' }}</td>
                                    <td>{{ $evaluation->evaluation->title ?? '-' }}</td>
                                    <td>{{ $evaluation->created_at->format('Y-m-d') }}</td>
                                 <td nowrap>
    <a href="{{ route('admin.coach_evaluation.edit', $evaluation->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('Edit') }}">
        <i class="la la-edit"></i>
    </a>

    <form action="{{ route('admin.coach_evaluation.destroy', $evaluation->id) }}" method="POST" class="delete-form d-inline-block">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('Delete') }}">
            <i class="la la-trash"></i>
        </button>
    </form>
</td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $coachEvaluations->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            @else
                <p class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i> {{ __('No evaluations found.') }}
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
