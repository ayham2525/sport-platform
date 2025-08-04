@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-user-circle mr-2"></i>{{ __('titles.profile') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i>{{ __('titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">
                <i class="fas fa-user mr-1"></i>{{ __('titles.profile') }}
            </span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">

    <!-- Evaluation Summary as Chart -->
    @if(count($evaluations))
        <div class="card card-custom mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>{{ __('titles.evaluation_summary') }}
                </h3>
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <canvas id="evaluationChart" style="min-width: 600px; max-height: 400px;"></canvas>
            </div>
        </div>
    @endif

    <!-- User Info -->
    <div class="card card-custom mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-circle mr-2"></i>{{ __('titles.profile') }}
            </h3>
        </div>
        <div class="card-body">
            <p><strong><i class="fas fa-user mr-1"></i>{{ __('columns.name') }}:</strong> {{ $user->name }}</p>
            <p><strong><i class="fas fa-envelope mr-1"></i>{{ __('columns.email') }}:</strong> {{ $user->email }}</p>
            <p><strong><i class="fas fa-user-tag mr-1"></i>{{ __('columns.role') }}:</strong> {{ ucfirst($user->role) }}</p>

            @if ($user->profile_image)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $user->profile_image) }}" class="img-thumbnail" width="150" alt="Profile Image">
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Profile Image -->
    <div class="card card-custom mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-image mr-2"></i>{{ __('titles.update_profile_image') }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.update_image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="profile_image" class="font-weight-bold">
                        <i class="fas fa-file-upload mr-1"></i>{{ __('columns.profile_image') }}
                    </label>
                    <input type="file" name="profile_image" class="form-control-file" required>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-upload mr-1"></i>{{ __('actions.update_image') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Update Account Info -->
    <div class="card card-custom mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-cog mr-2"></i>{{ __('titles.update_account') }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.update_account') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold">
                        <i class="fas fa-envelope mr-1"></i>{{ __('columns.email') }}
                    </label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">
                        <i class="fas fa-lock mr-1"></i>{{ __('columns.new_password') }}
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="{{ __('Leave blank if unchanged') }}">
                </div>

                <button type="submit" class="btn btn-sm btn-success">
                    <i class="fas fa-save mr-1"></i>{{ __('actions.update_account') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@if(count($evaluations))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('evaluationChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const labels = [];
        const data = [];

        @foreach ($evaluations as $evaluation)
            @foreach ($evaluation->responses as $response)
                @if (is_numeric($response->value))
                    labels.push(@json($response->criteria->label));
                    data.push({{ $response->value }});
                @endif
            @endforeach
            @break
        @endforeach

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '{{ __('titles.evaluation_scores') }}',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    });
</script>
@endif
