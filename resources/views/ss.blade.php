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
            ];

            if (in_array(Auth::user()->role, ['full_admin', 'system_admin'])) {
                $cards[] = ['key' => 'Payments',          'count' => $paymentsCount,                        'icon' => 'fas fa-money-bill-wave', 'color' => 'danger'];
                $cards[] = ['key' => 'PaidAmount',        'count' => number_format($paymentsTotalPaid, 2) . ' AED',      'icon' => 'fas fa-check-circle',   'color' => 'success'];
                $cards[] = ['key' => 'RemainingAmount',   'count' => number_format($paymentsTotalRemaining, 2) . ' AED', 'icon' => 'fas fa-clock',          'color' => 'warning'];
            }
        @endphp

        @foreach ($cards as $card)
            <div class="col-md-4 mb-4">
                <div class="card card-custom text-white bg-{{ $card['color'] }} card-stretch gutter-b">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <span><i class="{{ $card['icon'] }} fa-2x text-white"></i></span>
                        <div class="text-right">
                            <p class="font-weight-bold mb-1">{{ __('dashboard.' . $card['key']) }}</p>
                            <h2 class="font-weight-bolder mb-0">{{ $card['count'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (in_array(Auth::user()->role, ['full_admin', 'system_admin']))
        @php $currencyCode = $currencyCode ?? 'AED'; @endphp

        {{-- Monthly charts row (current year) --}}
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card card-custom h-100">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="la la-coins mr-1"></i>
                            {{ __('dashboard.charts.payments_title', ['currency' => $currencyCode, 'year' => now()->year]) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentsChart" height="160"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card card-custom h-100">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="la la-tshirt mr-1"></i>
                            {{ __('dashboard.charts.uniforms_title', ['year' => now()->year]) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="uniformsChart" height="160"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Yearly charts row (last 5 years) --}}
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card card-custom h-100">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="la la-chart-bar mr-1"></i>
                            {{ __('dashboard.charts.payments_title_yearly', ['currency' => $currencyCode]) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentsYearChart" height="160"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card card-custom h-100">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="la la-chart-line mr-1"></i>
                            {{ __('dashboard.charts.uniforms_title_yearly') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="uniformsYearChart" height="160"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@if (in_array(Auth::user()->role, ['full_admin', 'system_admin']))
<script>
(function(){
    const locale   = @json(app()->getLocale() ?: 'en');
    const fmtNum   = v => Intl.NumberFormat(locale, { notation: 'compact', maximumFractionDigits: 1 }).format(v);

    // ---- MONTHLY ----
    const monthsIx = @json($chartMonths ?? range(1,12));
    const labelsM  = monthsIx.map(m => new Date(2000, m-1, 1).toLocaleString(locale, { month: 'short' }));
    const paymentsMonthlyPaid   = @json($paymentsMonthlyPaid   ?? array_fill(0,12,0));
    const uniformsMonthlyAmount = @json($uniformsMonthlyAmount ?? array_fill(0,12,0));
    const uniformsMonthlyCount  = @json($uniformsMonthlyCount  ?? array_fill(0,12,0));

    const paidLabel    = @json(__('dashboard.charts.paid_label',     ['currency' => $currencyCode]));
    const uAmountLabel = @json(__('dashboard.charts.uniform_amount', ['currency' => $currencyCode]));
    const uCountLabel  = @json(__('dashboard.charts.uniform_count'));

    const elPayM = document.getElementById('paymentsChart');
    if (elPayM) {
        new Chart(elPayM, {
            type: 'bar',
            data: { labels: labelsM, datasets: [{ label: paidLabel, data: paymentsMonthlyPaid }] },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true, ticks: { callback: fmtNum } } }
            }
        });
    }

    const elUniM = document.getElementById('uniformsChart');
    if (elUniM) {
        new Chart(elUniM, {
            data: {
                labels: labelsM,
                datasets: [
                    { type: 'bar',  label: uAmountLabel, data: uniformsMonthlyAmount },
                    { type: 'line', label: uCountLabel,  data: uniformsMonthlyCount, yAxisID: 'y1' }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: {
                    y:  { beginAtZero: true, ticks: { callback: fmtNum } },
                    y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
                }
            }
        });
    }

    // ---- YEARLY ----
    const years    = @json($chartYears ?? []);
    const labelsY  = years.map(String);
    const paymentsYearlyPaid    = @json($paymentsYearlyPaid    ?? []);
    const uniformsYearlyAmount  = @json($uniformsYearlyAmount  ?? []);
    const uniformsYearlyCount   = @json($uniformsYearlyCount   ?? []);

    const elPayY = document.getElementById('paymentsYearChart');
    if (elPayY && years.length) {
        new Chart(elPayY, {
            type: 'bar',
            data: { labels: labelsY, datasets: [{ label: paidLabel, data: paymentsYearlyPaid }] },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true, ticks: { callback: fmtNum } } }
            }
        });
    }

    const elUniY = document.getElementById('uniformsYearChart');
    if (elUniY && years.length) {
        new Chart(elUniY, {
            data: {
                labels: labelsY,
                datasets: [
                    { type: 'bar',  label: uAmountLabel, data: uniformsYearlyAmount },
                    { type: 'line', label: uCountLabel,  data: uniformsYearlyCount, yAxisID: 'y1' }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: {
                    y:  { beginAtZero: true, ticks: { callback: fmtNum } },
                    y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
                }
            }
        });
    }
})();
</script>
@endif
@endpush
