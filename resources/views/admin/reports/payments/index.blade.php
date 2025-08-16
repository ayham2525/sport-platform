@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        {{ __('reports.payments.title') }}
    </h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('attendance.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('reports.payments.title') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="container">

    {{-- Filters --}}
    <form id="filters-form" method="GET" action="{{ route('admin.reports.payments.index') }}" class="card card-custom mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="la la-filter mr-1"></i> {{ __('reports.payments.filters.title') }}
            </h3>
            <div class="card-toolbar">
                @php $exportQuery = array_merge(request()->query(), ['export' => 'csv']); @endphp
                <a href="{{ route('admin.reports.payments.index', $exportQuery) }}" class="btn btn-sm btn-light-primary">
                    <i class="la la-download"></i> {{ __('reports.actions.export_csv') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">
                {{-- Date From --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.date_from') }}</label>
                    <input type="date" name="date_from" class="form-control"
                           value="{{ old('date_from', $filters['date_from'] ?? '') }}">
                </div>

                {{-- Date To --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.date_to') }}</label>
                    <input type="date" name="date_to" class="form-control"
                           value="{{ old('date_to', $filters['date_to'] ?? '') }}">
                </div>

                {{-- Status --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.status') }}</label>
                    <select name="status" class="form-control">
                        <option value="">{{ __('reports.payments.filters.any') }}</option>
                        <option value="pending" {{ request('status')==='pending' ? 'selected':'' }}>
                            {{ __('payment.status.pending') }}
                        </option>
                        <option value="partial" {{ request('status')==='partial' ? 'selected':'' }}>
                            {{ __('payment.status.partial') }}
                        </option>
                        <option value="paid" {{ request('status')==='paid' ? 'selected':'' }}>
                            {{ __('payment.status.paid') }}
                        </option>
                    </select>
                </div>

                {{-- Category --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.category') }}</label>
                    <select name="category" class="form-control">
                        <option value="">{{ __('reports.payments.filters.any') }}</option>
                        @foreach(\App\Models\Payment::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ request('category')===$key ? 'selected':'' }}>
                                {{ __('payment.categories.'.$key) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Branch (select) --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.branch') }}</label>
                    <select name="branch_id" class="form-control">
                        <option value="">{{ __('reports.payments.filters.any') }}</option>
                        @foreach(($branchOptions ?? []) as $id => $name)
                            <option value="{{ $id }}" {{ (string)request('branch_id') === (string)$id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Academy (select) --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.academy') }}</label>
                    <select name="academy_id" class="form-control">
                        <option value="">{{ __('reports.payments.filters.any') }}</option>
                        @foreach(($academyOptions ?? []) as $id => $name)
                            <option value="{{ $id }}" {{ (string)request('academy_id') === (string)$id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Program (still free numeric filter – update later if you want a select) --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.program') }}</label>
                    <input type="number" name="program_id" class="form-control"
                           value="{{ old('program_id', $filters['program_id'] ?? '') }}"
                           placeholder="{{ __('reports.payments.filters.program_placeholder') }}">
                </div>

                {{-- Player (free numeric) --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.player') }}</label>
                    <input type="number" name="player_id" class="form-control"
                           value="{{ old('player_id', $filters['player_id'] ?? '') }}"
                           placeholder="{{ __('reports.payments.filters.player_placeholder') }}">
                </div>

                {{-- Payment Method (select) --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.payment_method') }}</label>
                    <select name="payment_method_id" class="form-control">
                        <option value="">{{ __('reports.payments.filters.any') }}</option>
                        @foreach(($paymentMethodOptions ?? []) as $id => $name)
                            <option value="{{ $id }}" {{ (string)request('payment_method_id') === (string)$id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset number contains --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.reset_contains') }}</label>
                    <input type="text" name="reset_search" class="form-control"
                           value="{{ old('reset_search', $filters['reset_search'] ?? '') }}"
                           placeholder="{{ __('reports.payments.filters.reset_placeholder') }}">
                </div>

                {{-- Per Page --}}
                <div class="form-group col-md-3">
                    <label>{{ __('reports.payments.filters.per_page') }}</label>
                    <select name="per_page" class="form-control">
                        @foreach([25,50,100,200] as $pp)
                            <option value="{{ $pp }}" {{ request('per_page', 25)==$pp ? 'selected':'' }}>
                                {{ $pp }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.reports.payments.index') }}" class="btn btn-light-danger">
                <i class="la la-undo"></i> {{ __('reports.actions.reset') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="la la-search"></i> {{ __('reports.actions.apply_filters') }}
            </button>
        </div>
    </form>



    {{-- Status chips --}}
    <div class="mb-4">
        <span class="mr-3">{{ __('reports.payments.status_breakdown') }}:</span>
        @foreach (['pending','partial','paid'] as $s)
            <span class="{{ $mapClass[$s] ?? 'badge badge-light' }} mr-2">
                {{ __('payment.status.'.$s) }}:
                <strong>{{ $statusCounts[$s] ?? 0 }}</strong>
            </span>
        @endforeach
    </div>

    {{-- Payment method counts --}}
    @if(!empty($methodCounts))
        <div class="mb-4">
            @foreach($methodCounts as $mid => $cnt)
                <span class="badge badge-info mr-2">
                    {{ $methodNames[$mid] ?? ('#'.$mid) }}: <strong>{{ $cnt }}</strong>
                </span>
            @endforeach
        </div>
    @endif

    {{-- Results (AJAX target) --}}
    <div id="report-results">
        @include('admin.reports.payments.partials.results')
    </div>
</div>
@endsection

@push('styles')
<style>
    .report-table td, .report-table th { white-space: nowrap; vertical-align: middle; }
    #report-results.loading { opacity: .5; pointer-events: none; transition: opacity .2s ease; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const form = document.getElementById('filters-form');
    const results = document.getElementById('report-results');

    function serialize(formEl){
        return new URLSearchParams(new FormData(formEl)).toString();
    }

    function load(url){
        results.classList.add('loading');
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(r => r.text())
            .then(html => {
                results.innerHTML = html;
                history.replaceState(null, '', url);
            })
            .catch(() => alert('Failed to load report.'))
            .finally(() => results.classList.remove('loading'));
    }

    function submit(){
        const qs = serialize(form);
        load(form.action + (qs ? ('?' + qs) : ''));
    }

    // submit on change & on submit
    form.addEventListener('change', submit);
    form.addEventListener('submit', function(e){ e.preventDefault(); submit(); });

    // AJAX pagination
    document.addEventListener('click', function(e){
        const a = e.target.closest('#report-results .pagination a');
        if (!a) return;
        e.preventDefault();
        load(a.href);
    });
})();
</script>
@endpush
