@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('attendance.titles.view') }}</h5>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('attendance.titles.view') }}</h3>

            @if (PermissionHelper::hasPermission('create', \App\Models\Attendance::MODEL_NAME))
                <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary">
                    <i class="la la-plus-circle"></i> {{ __('attendance.create_attendance') }}
                </a>
            @endif
        </div>

        <div class="card-body">
          <form id="attendanceFilter" class="mb-3">
    @csrf
    <div class="form-row">
        {{-- System --}}
        <div class="form-group col-md-3">
            <label>{{ __('attendance.fields.system') }}</label>
            <select name="system_id" id="system_id" class="form-control"
                    @if(!in_array(auth()->user()->role, ['full_admin','system_admin']) && auth()->user()->system_id) disabled @endif>
                <option value="">{{ __('attendance.filters.any') }}</option>
                @foreach(($systems ?? collect()) as $s)
                    <option value="{{ $s->id }}" {{ (isset($systemId) && (int)$systemId === (int)$s->id) ? 'selected' : '' }}>
                        {{ $s->name ?? ('#'.$s->id) }}
                    </option>
                @endforeach
            </select>
            {{-- keep value if disabled --}}
            @if(!in_array(auth()->user()->role, ['full_admin','system_admin']) && auth()->user()->system_id)
                <input type="hidden" name="system_id" value="{{ auth()->user()->system_id }}">
            @endif
        </div>

        {{-- Branch --}}
        <div class="form-group col-md-3">
            <label>{{ __('attendance.fields.branch') }}</label>
            <select name="branch_id" id="branch_id" class="form-control"
                    @if(auth()->user()->role === 'branch_admin' && auth()->user()->branch_id) disabled @endif>
                <option value="">{{ __('attendance.filters.any') }}</option>
                @foreach(($branches ?? collect()) as $b)
                    <option value="{{ $b->id }}" {{ (isset($branchId) && (int)$branchId === (int)$b->id) ? 'selected' : '' }}>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
            {{-- keep value if disabled --}}
            @if(auth()->user()->role === 'branch_admin' && auth()->user()->branch_id)
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
            @endif
        </div>

        <div class="form-group col-md-2">
            <label>{{ __('attendance.fields.date_from') }}</label>
            <input type="date" name="date_from" class="form-control" value="{{ $startDate ?? '' }}">
        </div>
        <div class="form-group col-md-2">
            <label>{{ __('attendance.fields.date_to') }}</label>
            <input type="date" name="date_to" class="form-control" value="{{ $endDate ?? '' }}">
        </div>
        <div class="form-group col-md-2">
            <label>{{ __('attendance.fields.serial') }}</label>
            <input type="text" name="card_serial_number" class="form-control" placeholder="e.g. 04A3...">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">
                <i class="la la-search"></i> {{ __('attendance.action.filter') }}
            </button>
        </div>
    </div>
</form>


            <div id="attendanceTable"><!-- AJAX injects here --></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const form = document.getElementById('attendanceFilter');
    const tableDiv = document.getElementById('attendanceTable');
    let currentPage = 1;

    function bindTableEvents(){
        // pagination
        tableDiv.querySelectorAll('.ajax-page').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const p = parseInt(a.dataset.page, 10) || 1;
                load(p);
            });
        });

        // delete
        tableDiv.querySelectorAll('.ajax-delete').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const url = btn.dataset.url;
                if (!url) return;

                if (!confirm('{{ __('attendance.confirm_delete') ?? 'Delete this record?' }}')) return;

                const fd = new FormData();
                fd.set('_token', form.querySelector('input[name=_token]').value);
                fd.set('_method', 'DELETE');

                fetch(url, { method: 'POST', body: fd })
                  .then(r => r.ok ? r : Promise.reject())
                  .then(() => load(currentPage))
                  .catch(() => alert('{{ __('attendance.delete_failed') ?? 'Delete failed' }}'));
            });
        });

        // read current page from partial (if provided)
        const holder = tableDiv.querySelector('.attendance-table');
        if (holder && holder.dataset.currentPage) {
            currentPage = parseInt(holder.dataset.currentPage, 10) || 1;
        }
    }

    function load(page=1){
        currentPage = page;
        const fd = new FormData(form);
        fd.set('page', page);

        fetch("{{ route('admin.attendance.search') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': fd.get('_token') },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                tableDiv.innerHTML = data.html;
                bindTableEvents();
            }
        });
    }

    form.addEventListener('submit', e => { e.preventDefault(); load(1); });
    load(1); // initial
})();
</script>
@endpush
@push('scripts')
<script>
(function(){
    const form = document.getElementById('attendanceFilter');
    const tableDiv = document.getElementById('attendanceTable');
    const systemSel = document.getElementById('system_id');
    const branchSel = document.getElementById('branch_id');
    let currentPage = 1;

    function setBranchOptions(list, keepSelected = false) {
        const prev = keepSelected ? branchSel.value : '';
        branchSel.innerHTML = '';
        const any = document.createElement('option');
        any.value = '';
        any.textContent = "{{ __('attendance.filters.any') }}";
        branchSel.appendChild(any);

        list.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.id;
            opt.textContent = b.name;
            branchSel.appendChild(opt);
        });

        if (keepSelected && prev) {
            branchSel.value = prev;
            if (branchSel.value !== prev) {
                // previously selected branch not in new list
                branchSel.value = '';
            }
        }
    }

    const urlTemplate = "{{ route('admin.getBranchesBySystem', ['system_id' => 'SID']) }}";

function fetchBranchesBySystem(systemId) {
  if (!branchSel) return;
  setBranchOptions([], false);

  const url = urlTemplate.replace('SID', encodeURIComponent(systemId || ''));
  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
    .then(r => r.json())
    .then(data => setBranchOptions(Array.isArray(data) ? data : (data.data || [])))
    .catch(() => setBranchOptions([]));
}

    function bindTableEvents(){
        tableDiv.querySelectorAll('.ajax-page').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const p = parseInt(a.dataset.page, 10) || 1;
                load(p);
            });
        });

        tableDiv.querySelectorAll('.ajax-delete').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const url = btn.dataset.url;
                if (!url) return;

                if (!confirm('{{ __('attendance.confirm_delete') ?? 'Delete this record?' }}')) return;

                const fd = new FormData();
                fd.set('_token', form.querySelector('input[name=_token]').value);
                fd.set('_method', 'DELETE');

                fetch(url, { method: 'POST', body: fd })
                  .then(r => r.ok ? r : Promise.reject())
                  .then(() => load(currentPage))
                  .catch(() => alert('{{ __('attendance.delete_failed') ?? 'Delete failed' }}'));
            });
        });

        const holder = tableDiv.querySelector('.attendance-table');
        if (holder && holder.dataset.currentPage) {
            currentPage = parseInt(holder.dataset.currentPage, 10) || 1;
        }
    }

    function load(page=1){
        currentPage = page;
        const fd = new FormData(form);
        fd.set('page', page);

        fetch("{{ route('admin.attendance.search') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': fd.get('_token') },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                tableDiv.innerHTML = data.html;
                bindTableEvents();
            }
        });
    }

    // Auto fetch branches on system change, then reload table
    if (systemSel && !systemSel.disabled) {
        systemSel.addEventListener('change', () => {
            const sid = systemSel.value || '';
            fetchBranchesBySystem(sid);
            // reset branch filter when system changes
            if (branchSel && !branchSel.disabled) branchSel.value = '';
            // auto search after change
            load(1);
        });
    }

    if (branchSel && !branchSel.disabled) {
        branchSel.addEventListener('change', () => load(1));
    }

    form.addEventListener('submit', e => { e.preventDefault(); load(1); });

    // Initial load: if system preset, ensure branches list matches it
    if (systemSel && systemSel.value && !branchSel.disabled) {
        fetchBranchesBySystem(systemSel.value);
    }

    load(1);
})();
</script>
@endpush
