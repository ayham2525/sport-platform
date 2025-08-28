@extends('layouts.app')

<style>
.table-nowrap td, .table-nowrap th { white-space: nowrap; vertical-align: middle; }
.table-nowrap td { overflow: hidden; text-overflow: ellipsis; max-width: 400px; font-size: 12px; }
.table-scroll { overflow-x: auto; overflow-y: auto; max-width: 100%; max-height: 70vh; -webkit-overflow-scrolling: touch; border-radius: .35rem; }
.table-scroll table { width: max-content; min-width: 100%; margin-bottom: 0; }
.table-scroll::-webkit-scrollbar { height: 10px; width: 10px; }
.table-scroll::-webkit-scrollbar-thumb { background: #cfd4da; border-radius: 6px; }
.table-scroll::-webkit-scrollbar-track { background: #f1f3f5; }
.badge-program { cursor: pointer; }
</style>

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">
    {{ __('branch.players_in', ['branch' => $branch->name]) }}
</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('branch.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.branches.index') }}" class="text-muted">
            <i class="la la-building mr-1"></i> {{ __('branch.title') }}
        </a>
    </li>
    <li class="breadcrumb-item text-primary">
        <i class="la la-map-marker-alt mr-1"></i> {{ $branch->name }}
    </li>
</ul>
@endsection

@section('content')
@php
    $tr = function (string $key, string $fallback) {
        $v = __($key);
        return is_array($v) ? $fallback : $v;
    };
    $isAr = app()->getLocale() === 'ar';
@endphp

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label">
                        {{ __('branch.players_in', ['branch' => $branch->name]) }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('branch.management') }}</span>
                    </h3>
                </div>

                <div class="card-toolbar d-flex align-items-center">
                    {{-- Status chips --}}
                    <div class="btn-group mr-3" role="group">
                        <button id="chip-active" class="btn btn-sm btn-light-success">
                            {{ $tr('player.status.active', 'Active') }}:
                            <strong id="count-active">{{ number_format($activeCount ?? 0) }}</strong>
                        </button>
                        <button id="chip-expired" class="btn btn-sm btn-danger">
                            {{ $tr('player.status.expired', 'Expired') }}:
                            <strong id="count-expired">{{ number_format($expiredCount ?? 0) }}</strong>
                        </button>
                        <button id="chip-reset" class="btn btn-sm btn-light">
                            {{ $tr('player.reset', 'Reset') }}
                        </button>
                    </div>

                    {{-- Academy filter --}}
                    <div class="mr-3">
                        <select id="academy-filter" class="form-control form-control-sm">
                            <option value="">
                                {{ $tr('player.academy', 'Academy') }} — {{ $tr('branch.all', 'All') }}
                            </option>
                            @foreach($academies ?? [] as $ac)
                                <option value="{{ $ac['id'] }}">
                                    {{ $isAr ? ($ac['name_ar'] ?? $ac['name_en']) : ($ac['name_en'] ?? $ac['name_ar']) }}
                                    ({{ $ac['total'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Program filter --}}
                    <div class="mr-3">
                        <select id="program-filter" class="form-control form-control-sm">
                            <option value="">{{ $tr('program.title', 'Program') }} — {{ $tr('branch.all', 'All') }}</option>
                        </select>
                    </div>

                    {{-- Export --}}
                    <a id="export-btn" href="#" class="btn btn-sm btn-light-primary">
                        <i class="la la-file-excel"></i> {{ $tr('reports.actions.export_excel', 'Export Excel') }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-4">
                    <input type="text" id="search-input" class="form-control" placeholder="{{ $tr('branch.search_placeholder', 'Search...') }}">
                </div>

                <div class="table-responsive">
                    <table class="table table-separate table-head-custom table-checkable table-nowrap" id="players-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>{{ $tr('player.name', 'Name') }}</th>
                                <th>{{ $tr('player.email', 'Email') }}</th>
                                <th>{{ $tr('player.guardian_phone', 'Guardian Phone') }}</th>
                                <th>{{ $tr('player.sport', 'Sport') }}</th>
                                <th>{{ $tr('program.title', 'Program') }}</th>
                                <th>{{ $tr('player.academy', 'Academy') }}</th>
                                <th>{{ $tr('player.branch', 'Branch') }}</th>
                                <th>{{ $tr('player.status.title', 'Status') }}</th>
                                <th>{{ $tr('player.fields.card_serial_number', 'Card Serial Number') }}</th>
                                 <th>{{ $tr('player.created_at', 'Created at') }}</th>
                                <th>{{ $tr('player.actions', 'Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="players-body">
                            {{-- AJAX --}}
                        </tbody>
                    </table>
                </div>

                <div id="pagination" class="mt-4 text-center"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput     = document.getElementById('search-input');
    const academyFilter   = document.getElementById('academy-filter');
    const programFilter   = document.getElementById('program-filter');
    const tbody           = document.getElementById('players-body');
    const pagination      = document.getElementById('pagination');
    const chipActive      = document.getElementById('chip-active');
    const chipExpired     = document.getElementById('chip-expired');
    const chipReset       = document.getElementById('chip-reset');
    const countActiveEl   = document.getElementById('count-active');
    const countExpiredEl  = document.getElementById('count-expired');
    const exportBtn       = document.getElementById('export-btn');

    let debounceTimer = null;
    let currentStatus = null;         // 'active' | 'expired' | null
    let currentAcademyId = '';        // '' or numeric
    let currentProgramId = '';        // '' or numeric

    const T_ACTIVE   = @json(__('player.status.active'));
    const T_EXPIRED  = @json(__('player.status.expired'));
    const VIEW_TXT   = @json(__('branch.view'));
    const BASE_ROUTE = @json(route('admin.branches.players', $branch->id));

    function buildUrl(page = 1, forExport = false) {
        const url = new URL(BASE_ROUTE, window.location.origin);
        const q   = searchInput.value.trim();
        if (q.length)           url.searchParams.set('search', q);
        if (page)               url.searchParams.set('page', page);
        if (currentStatus)      url.searchParams.set('status', currentStatus);
        if (currentAcademyId)   url.searchParams.set('academy_id', currentAcademyId);
        if (currentProgramId)   url.searchParams.set('program_id', currentProgramId);
        if (forExport)          url.searchParams.set('export', 'excel');
        return url;
    }

    function populatePrograms(list) {
        const keep = currentProgramId;
        programFilter.innerHTML = '';
        const optAll = document.createElement('option');
        optAll.value = '';
        optAll.textContent = @json($tr('branch.all', 'All')) + ' ' + @json($tr('program.title', 'Programs'));
        programFilter.appendChild(optAll);

        list.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = p.name;
            programFilter.appendChild(opt);
        });

        // Keep selection if still present
        if (keep && list.some(p => String(p.id) === String(keep))) {
            programFilter.value = keep;
        } else {
            currentProgramId = '';
            programFilter.value = '';
        }
    }

    function loadPlayers(page = 1) {
        const url = buildUrl(page, false);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(data => render(data))
            .catch(err => console.error('Load error:', err));
    }

    function render(data) {
        // Chips counts
        if (data.counts) {
            countActiveEl.textContent  = new Intl.NumberFormat().format(data.counts.active || 0);
            countExpiredEl.textContent = new Intl.NumberFormat().format(data.counts.expired || 0);
        }

        // Program dropdown (sent on every response, filtered by academy server-side)
        if (Array.isArray(data.programs)) {
            populatePrograms(data.programs);
        }

        // Table
        tbody.innerHTML = '';
        if (!data.players.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="12" class="text-center">{{ $tr('branch.no_players', 'No players found') }}</td>
                </tr>`;
            pagination.innerHTML = '';
            return;
        }

        const start = Number(data.pagination.from) || 1;

        data.players.forEach((p, i) => {
            const rowNum    = start + i;
            const badgeText = p.status === 'active' ? T_ACTIVE : T_EXPIRED;
            const badgeCls  = p.status === 'active' ? 'badge-success' : 'badge-danger';

            // Program chips
            const programChips = (p.programs || [])
              .map(pr => `<span class="badge badge-info badge-program mr-1 mb-1" data-program-id="${pr.id}">${pr.name}</span>`)
              .join('') || '-';

    const scanRouteTemplate = "{{ route('admin.cards.scan', ['player_id' => ':id']) }}";

            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${rowNum}</td>
                    <td>${p.id ?? '-'}</td>
                    <td>${p.name ?? '-'}</td>
                    <td>${p.email ?? '-'}</td>
                    <td>${p.phone ?? '-'}</td>
                    <td>${p.sport ?? '-'}</td>
                    <td>${programChips}</td>
                    <td>${p.academy ?? '-'}</td>
                    <td>${p.branch ?? '-'}</td>
                    <td><span class="badge ${badgeCls}">${badgeText}</span></td>
                    <td>${p.card_serial_number ?? '-'}</td>
                    <td>${p.created_at ?? '-'}</td>
                    <td>
            <a href="/admin/players/${p.id}" class="btn btn-sm btn-clean btn-icon" title="${VIEW_TXT}">
                <i class="la la-eye"></i>
            </a>
            <a href="${scanRouteTemplate.replace(':id', p.id)}"
               class="btn btn-sm btn-clean btn-icon"
               title="{{ __('player.actions.scan_card') }}">
                <i class="la la-id-card"></i>
            </a>
        </td>
                </tr>
            `);
        });

        // Pagination
        renderPagination(data.pagination);
    }

    function renderPagination(p) {
        pagination.innerHTML = '';
        for (let i = 1; i <= p.last_page; i++) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm mx-1 ' + (i === p.current_page ? 'btn-primary' : 'btn-light');
            btn.textContent = i;
            btn.onclick = () => loadPlayers(i);
            pagination.appendChild(btn);
        }
    }

    // Chips
    chipActive.addEventListener('click', () => { currentStatus = 'active';  loadPlayers(1); });
    chipExpired.addEventListener('click', () => { currentStatus = 'expired'; loadPlayers(1); });
    chipReset.addEventListener('click', () => {
        currentStatus    = null;
        currentAcademyId = '';
        currentProgramId = '';
        academyFilter.value = '';
        programFilter.value = '';
        loadPlayers(1);
    });

    // Academy filter → resets program filter and reloads
    academyFilter.addEventListener('change', (e) => {
        currentAcademyId = e.target.value || '';
        currentProgramId = '';
        programFilter.value = '';
        loadPlayers(1);
    });

    // Program filter
    programFilter.addEventListener('change', (e) => {
        currentProgramId = e.target.value || '';
        loadPlayers(1);
    });

    // Clickable program chips in the table (event delegation)
    tbody.addEventListener('click', (e) => {
        const chip = e.target.closest('.badge-program');
        if (!chip) return;
        const pid = chip.getAttribute('data-program-id');
        if (!pid) return;
        currentProgramId = pid;
        programFilter.value = pid;
        loadPlayers(1);
    });

    // Export
    exportBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const url = buildUrl(1, true);
        window.location.href = url.toString();
    });

    // Search (debounced)
    searchInput.addEventListener('keyup', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        if (q.length >= 3 || q.length === 0) {
            debounceTimer = setTimeout(() => loadPlayers(1), 300);
        }
    });

    // Initial load
    loadPlayers();
});
</script>
@endpush
