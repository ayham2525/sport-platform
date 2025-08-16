@extends('layouts.app')
<style>
    /* existing */
    .table-nowrap td,
    .table-nowrap th {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-nowrap td {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px; /* adjust as needed */
        font-size: 12px;
    }

    /* NEW: scrolling container */
    .table-scroll {
        overflow-x: auto;
        overflow-y: auto;
        max-width: 100%;
        max-height: 70vh;          /* adjust height as needed */
        -webkit-overflow-scrolling: touch;
        border-radius: .35rem;
    }

    /* Let table expand beyond container to trigger horizontal scroll */
    .table-scroll table {
        width: max-content;
        min-width: 100%;
        margin-bottom: 0;
    }

    /* Optional: nicer scrollbars (webkit) */
    .table-scroll::-webkit-scrollbar {
        height: 10px;
        width: 10px;
    }
    .table-scroll::-webkit-scrollbar-thumb {
        background: #cfd4da;
        border-radius: 6px;
    }
    .table-scroll::-webkit-scrollbar-track {
        background: #f1f3f5;
    }
</style>

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('reports.branch_summary.title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('attendance.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('reports.branch_summary.title') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <form method="GET" action="{{ route('admin.reports.payments.branch_summary') }}" class="card card-custom mb-5" id="filters-form">
        <div class="card-header">
            <h3 class="card-title"><i class="la la-filter mr-1"></i> {{ __('reports.branch_summary.filters.title') }}</h3>
        </div>

        {{-- Export + range in the filter card header (kept as you had it) --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="la la-table mr-1"></i> {{ __('reports.branch_summary.table.title') }}
            </h3>
            <div class="d-flex align-items-center">
                <div class="text-muted mr-3">
                    {{ __('reports.branch_summary.table.range') }}:
                    <strong>{{ $dateFrom }} → {{ $dateTo }}</strong>
                </div>
                @php $exportQuery = array_merge(request()->query(), ['export' => 'excel']); @endphp
                <a href="{{ route('admin.reports.payments.branch_summary', $exportQuery) }}"
                   class="btn btn-sm btn-light-primary">
                    <i class="la la-file-excel"></i> {{ __('reports.actions.export_excel') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">
                {{-- System --}}
                <div class="form-group col-md-4">
                    <label>{{ __('reports.branch_summary.filters.system') }}</label>
                    @if(auth()->user()->role === 'full_admin')
                        <select name="system_id" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ __('reports.branch_summary.filters.choose_system') }}</option>
                            @foreach($systems as $sys)
                                <option value="{{ $sys->id }}" {{ (string)request('system_id')===(string)$sys->id ? 'selected':'' }}>
                                    {{ $sys->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        @php $sys = $systems->first(); @endphp
                        <input type="hidden" name="system_id" value="{{ $sys->id }}">
                        <input type="text" class="form-control" value="{{ $sys->name }}" disabled>
                    @endif
                </div>

                {{-- Branch --}}
                <div class="form-group col-md-4">
                    <label>{{ __('reports.branch_summary.filters.branch') }}</label>
                    <select name="branch_id" class="form-control" onchange="this.form.submit()">
                        <option value="">{{ __('reports.branch_summary.filters.all_branches') }}</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ (string)request('branch_id')===(string)$b->id ? 'selected':'' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date From --}}
                <div class="form-group col-md-2">
                    <label>{{ __('reports.branch_summary.filters.date_from') }}</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>

                {{-- Date To --}}
                <div class="form-group col-md-2">
                    <label>{{ __('reports.branch_summary.filters.date_to') }}</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.reports.payments.branch_summary') }}" class="btn btn-light-danger">
                <i class="la la-undo"></i> {{ __('reports.actions.reset') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="la la-search"></i> {{ __('reports.actions.apply_filters') }}
            </button>
        </div>
    </form>

    {{-- Branch table --}}
    <div class="card card-custom mb-5">
        <div class="card-header">
            <h3 class="card-title"><i class="la la-table mr-1"></i> {{ __('reports.branch_summary.table.title') }}</h3>
            <div class="card-toolbar text-muted">
                {{ __('reports.branch_summary.table.range') }}:
                <strong>{{ $dateFrom }} → {{ $dateTo }}</strong>
            </div>
        </div>
        <div class="card-body">
            @if($branchRows->count())
                <div class="table-scroll">
                    <table class="table table-bordered table-hover table-sm report-table table-nowrap">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('reports.branch_summary.table.branch') }}</th>
                            <th class="text-right">{{ __('reports.branch_summary.table.total_income') }}</th>
                            <th class="text-right">{{ __('reports.branch_summary.table.expired') }}</th>
                            @foreach($paymentMethods as $m)
                                @php
                                    $label = app()->getLocale() === 'ar'
                                        ? ($m->name_ar ?? $m->name)
                                        : (app()->getLocale() === 'ur'
                                            ? ($m->name_ur ?? $m->name)
                                            : $m->name);
                                @endphp
                                <th class="text-right">{{ $label }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($branchRows as $r)
                            <tr @if((string)$selectedBranchId === (string)$r->branch_id) class="table-primary" @endif>
                                <td>{{ $r->branch_name }}</td>
                                <td class="text-right font-weight-bold">{{ number_format($r->total_income, 2) }}</td>
                                <td class="text-right">{{ number_format($r->expired) }}</td>
                                @foreach($paymentMethods as $m)
                                    <td class="text-right">{{ number_format($r->methods[$m->id] ?? 0, 2) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="font-weight-bold">
                            <td>{{ __('reports.branch_summary.table.total') }}</td>
                            <td class="text-right">{{ number_format($branchRows->sum('total_income'), 2) }}</td>
                            <td class="text-right">{{ number_format($branchRows->sum('expired')) }}</td>
                            @foreach($paymentMethods as $m)
                                @php
                                    $sumCol = $branchRows->sum(fn($r) => (float) ($r->methods[$m->id] ?? 0));
                                @endphp
                                <td class="text-right">{{ number_format($sumCol, 2) }}</td>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">{{ __('reports.table.no_results') }}</p>
            @endif
        </div>
    </div>

    {{-- Academy table (visible when a branch is selected) --}}
    @if($selectedBranchId)
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title"><i class="la la-table mr-1"></i> {{ __('reports.branch_summary.academy_title') }}</h3>
        </div>
        <div class="card-body">
            @if($academyRows->count())
                <div class="table-scroll">
                    <table class="table table-bordered table-hover table-sm report-table table-nowrap">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('reports.branch_summary.table.academy') }}</th>
                            <th class="text-right">{{ __('reports.branch_summary.table.total_income') }}</th>
                            <th class="text-right">{{ __('reports.branch_summary.table.expired') }}</th>
                            @foreach($paymentMethods as $m)
                                @php
                                    $label = app()->getLocale() === 'ar'
                                        ? ($m->name_ar ?? $m->name)
                                        : (app()->getLocale() === 'ur'
                                            ? ($m->name_ur ?? $m->name)
                                            : $m->name);
                                @endphp
                                <th class="text-right">{{ $label }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($academyRows as $r)
                            <tr>
                                <td>{{ $r->academy_name }}</td>
                                <td class="text-right font-weight-bold">{{ number_format($r->total_income, 2) }}</td>
                                <td class="text-right">{{ number_format($r->expired) }}</td>
                                @foreach($paymentMethods as $m)
                                    <td class="text-right">{{ number_format($r->methods[$m->id] ?? 0, 2) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="font-weight-bold">
                            <td>{{ __('reports.branch_summary.table.total') }}</td>
                            <td class="text-right">{{ number_format($academyRows->sum('total_income'), 2) }}</td>
                            <td class="text-right">{{ number_format($academyRows->sum('expired')) }}</td>
                            @foreach($paymentMethods as $m)
                                @php
                                    $sumCol = $academyRows->sum(fn($r) => (float) ($r->methods[$m->id] ?? 0));
                                @endphp
                                <td class="text-right">{{ number_format($sumCol, 2) }}</td>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">{{ __('reports.table.no_results') }}</p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
