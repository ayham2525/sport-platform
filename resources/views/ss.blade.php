@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('messages.Welcome') }} {{ Auth::user()->name }}</h5>
@endsection

@section('content')
<div class="container">
    <div class="row">
    @php
        $cards = [
            ['key' => 'Programs', 'count' => $programsCount, 'icon' => 'fas fa-list', 'color' => 'primary'],
            ['key' => 'Classes', 'count' => $classesCount, 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'success'],
            ['key' => 'Players', 'count' => $playersCount, 'icon' => 'fas fa-users', 'color' => 'info'],
            ['key' => 'Coaches', 'count' => $coachesCount, 'icon' => 'fas fa-user-tie', 'color' => 'warning'],
            ['key' => 'Academies', 'count' => $academiesCount, 'icon' => 'fas fa-university', 'color' => 'danger'],
            ['key' => 'Branches', 'count' => $branchesCount, 'icon' => 'fas fa-code-branch', 'color' => 'dark'],
            ['key' => 'Payments', 'count' => $paymentsCount, 'icon' => 'fas fa-money-bill-wave', 'color' => 'danger'],
            ['key' => 'PaidAmount', 'count' => number_format($paymentsTotalPaid, 2) . ' AED', 'icon' => 'fas fa-check-circle', 'color' => 'success'],
            ['key' => 'RemainingAmount', 'count' => number_format($paymentsTotalRemaining, 2) . ' AED', 'icon' => 'fas fa-clock', 'color' => 'warning'],
        ];
    @endphp

    @foreach ($cards as $card)
    <div class="col-md-4 mb-4">
        <div class="card card-custom text-white bg-{{ $card['color'] }} card-stretch gutter-b">
            <div class="card-body d-flex align-items-center justify-content-between">
                <span>
                    <i class="{{ $card['icon'] }} fa-2x text-white"></i>
                </span>
                <div class="text-right">
                    <p class="font-weight-bold mb-1">{{ __('dashboard.' . $card['key']) }}</p>
                    <h2 class="font-weight-bolder mb-0">{{ $card['count'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
@endsection
