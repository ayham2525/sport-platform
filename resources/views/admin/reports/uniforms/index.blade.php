@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        {{ __('uniform_reports.title') }}
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
        <span class="text-muted">{{ __('uniform_reports.title') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <form id="filters-form" method="GET" action="{{ route('admin.reports.uniforms.index') }}" class="card card-custom mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="la la-filter mr-1"></i> {{ __('uniform_reports.filters.title') }}
            </h3>
            <div class="card-toolbar">
                @php $exportQuery = array_merge(request()->query(), ['export' => 'csv']); @endphp
                <a href="{{ route('admin.reports.uniforms.index', $exportQuery) }}" class="btn btn-sm btn-light-primary">
                    <i class="la la-download"></i> {{ __('reports.actions.export_csv') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">
                {{-- Dates --}}
                <div class="form-group col-md-3">
                    <label>{{ __('uniform_reports.filters.date_from') }}</label>
                    <input type="date" name="date_from" class="form-control" value="{{ old('date_from', $filters['date_from'] ?? '') }}">
                </div>
                <div class="form-group col-md-3">
                    <label>{{ __('uniform_reports.filters.date_to') }}</label>
                    <input type="date" name="date_to" class="form-control" value="{{ old('date_to', $filters['date_to'] ?? '') }}">
                </div>

                {{-- Statuses --}}
                <div class="form-group col-md-2">
                    <label>{{ __('uniform_reports.filters.status') }}</label>
                    <select name="status" class="form-control">
                        <option value="">{{ __('uniform_reports.filters.any') }}</option>
                        @foreach(\App\Models\UniformRequest::STATUS_OPTIONS as $k=>$v)
                            <option value="{{ $k }}" {{ ($filters['status']??'')===$k?'selected':'' }}>{{ __('uniform_requests.statuses.'.$k) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>{{ __('uniform_reports.filters.branch_status') }}</label>
                    <select name="branch_status" class="form-control">
                        <option value="">{{ __('uniform_reports.filters.any') }}</option>
                        @foreach(\App\Models\UniformRequest::BRANCH_STATUS_OPTIONS as $k=>$v)
                            <option value="{{ $k }}" {{ ($filters['branch_status']??'')===$k?'selected':'' }}>{{ __('uniform_requests.branch_statuses.'.$k) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>{{ __('uniform_reports.filters.office_status') }}</label>
                    <select name="office_status" class="form-control">
                        <option value="">{{ __('uniform_reports.filters.any') }}</option>
                        @foreach(\App\Models\UniformRequest::OFFICE_STATUS_OPTIONS as $k=>$v)
                            <option value="{{ $k }}" {{ ($filters['office_status']??'')===$k?'selected':'' }}>{{ __('uniform_requests.office_statuses.'.$k) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Branch / Academy (AJAX) --}}
                <div class="form-group col-md-3">
                    <label>{{ __('uniform_reports.filters.branch') }}</label>
                    <select name="branch_id" id="branch_id" class="form-control select2">
                        <option value="">{{ __('uniform_reports.filters.any') }}</option>
                        @foreach($branchOptions as $id=>$name)
                            <option value="{{ $id }}" {{ ($filters['branch_id']??'') == $id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>{{ __('uniform_reports.filters.academy') }}</label>
                    <select name="academy_id" id="academy_id" class="form-control">
                        <option value="">{{ __('uniform_reports.filters.any') }}</option>
                        @foreach($academyOptions as $id=>$name)
                            <option value="{{ $id }}" {{ ($filters['academy_id']??'') == $id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Player / Item --}}
                <div class="form-group col-md-3">
                    <label>{{ __('uniform_reports.filters.player') }}</label>
                    <select name="player_id" class="form-control select2">
                        <option value="">{{ __('uniform_reports.filters.any') }}</option>
                        @foreach($playerOptions as $id=>$name)
                            <option value="{{ $id }}" {{ ($filters['player_id']??'') == $id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>{{ __('uniform_reports.filters.item') }}</label>
                    <select name="item_id" class="form-control">
                        <option value="">{{ __('uniform_reports.filters.any') }}</option>
                        @foreach($itemOptions as $id=>$name)
                            <option value="{{ $id }}" {{ ($filters['item_id']??'') == $id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Payment method (free text in your schema) --}}
                <div class="form-group col-md-3">
                    <label>{{ __('uniform_reports.filters.payment_method') }}</label>
                    <input type="text" name="payment_method" class="form-control"
                        value="{{ old('payment_method', $filters['payment_method'] ?? '') }}"
                        placeholder="{{ __('uniform_reports.filters.payment_method_placeholder') }}">
                </div>

                {{-- Per Page --}}
                <div class="form-group col-md-2">
                    <label>{{ __('uniform_reports.filters.per_page') }}</label>
                    <select name="per_page" class="form-control">
                        @foreach([25,50,100,200] as $pp)
                            <option value="{{ $pp }}" {{ ($filters['per_page']??25)==$pp ? 'selected':'' }}>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.reports.uniforms.index') }}" class="btn btn-light-danger">
                <i class="la la-undo"></i> {{ __('reports.actions.reset') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="la la-search"></i> {{ __('reports.actions.apply_filters') }}
            </button>
        </div>
    </form>

    {{-- Results (AJAX target) --}}
    <div id="report-results">
        @include('admin.reports.uniforms.partials.results')
    </div>
</div>
@endsection

@push('styles')
<style>
    .report-table td, .report-table th { white-space: nowrap; vertical-align: middle; }
    #report-results.loading { opacity: .5; pointer-events: none; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const form = document.getElementById('filters-form');
    const results = document.getElementById('report-results');
    const branchSelect = document.getElementById('branch_id');
    const academySelect = document.getElementById('academy_id');

    // Safely pass translated strings / selected IDs from Blade to JS
    const anyLabel = @json(__('uniform_reports.filters.any'));
    const selectedAcademyId = @json($filters['academy_id'] ?? null);

    function serialize(formEl){ return new URLSearchParams(new FormData(formEl)).toString(); }

    function load(url){
        results.classList.add('loading');
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(r => r.text())
            .then(html => { results.innerHTML = html; history.replaceState(null, '', url); })
            .catch(() => alert('Failed to load report.'))
            .finally(() => results.classList.remove('loading'));
    }

    function submit(){
        const qs = serialize(form);
        load(form.action + (qs ? ('?' + qs) : ''));
    }

    form.addEventListener('change', submit);
    form.addEventListener('submit', function(e){ e.preventDefault(); submit(); });

    document.addEventListener('click', function(e){
        const a = e.target.closest('#report-results .pagination a');
        if (!a) return;
        e.preventDefault();
        load(a.href);
    });

    branchSelect && branchSelect.addEventListener('change', function(){
        const branchId = this.value || '';
        academySelect.innerHTML = '<option value="">' + anyLabel + '</option>';

        if (!branchId) { submit(); return; }

        fetch(@json(route('admin.reports.uniforms.academies')), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': @json(csrf_token()),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ branch_id: branchId })
        })
        .then(res => res.json())
        .then(json => {
            (json.data || []).forEach(function(a){
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = a.name;
                if (String(selectedAcademyId) === String(a.id)) opt.selected = true;
                academySelect.appendChild(opt);
            });
            submit();
        })
        .catch(() => { /* silent */ });
    });
})();
</script>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
@endpush

