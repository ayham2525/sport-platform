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
                    <div class="form-group col-md-3">
                        <label>{{ __('attendance.fields.date_from') }}</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('attendance.fields.date_to') }}</label>
                        <input type="date" name="date_to" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('attendance.fields.serial') }}</label>
                        <input type="text" name="card_serial_number" class="form-control" placeholder="e.g. 04A3...">
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
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
