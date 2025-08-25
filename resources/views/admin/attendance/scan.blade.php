@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('attendance.titles.take') }}</h5>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header d-flex flex-column">
            <h3 class="card-title">
                <i class="la la-credit-card text-primary"></i> {{ __('card.tap_card') }}
            </h3>
        </div>
        <div class="card-body">
            <div id="nfcStatus" class="mb-3 text-info">{{ __('card.waiting_to_start') }}</div>

            <form id="scanForm">
                @csrf
                <div class="form-group">
                    <label class="d-flex align-items-center">
                        <i class="la la-barcode mr-2"></i>
                        <span>{{ __('attendance.fields.serial') }}</span>
                    </label>
                    <input type="text" name="card_serial_number" id="card_serial_number"
                           class="form-control text-center" autofocus required autocomplete="off"
                           inputmode="numeric" placeholder="{{ __('card.tap_now') }}">
                </div>
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-2" id="saveBtn" disabled>
                        <i class="la la-save"></i> {{ __('player.actions.save') ?? 'Save' }}
                    </button>
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                        <i class="la la-list"></i> {{ __('attendance.action.view') }}
                    </a>
                </div>
            </form>

            <hr>
            <h6 class="mb-3">{{ __('attendance.titles.view') }}</h6>
            <div id="scanResults"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input   = document.getElementById('card_serial_number');
    const status  = document.getElementById('nfcStatus');
    const saveBtn = document.getElementById('saveBtn');
    const results = document.getElementById('scanResults');
    const form    = document.getElementById('scanForm');

    function toggle(){
        if (input.value.trim()) {
            status.textContent = "✅ {{ __('card.card_scanned') }}: " + input.value;
            saveBtn.disabled = false;
        } else {
            status.textContent = "⏳ {{ __('card.waiting_to_start') }}";
            saveBtn.disabled = true;
        }
    }

    input.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); toggle(); }});
    input.addEventListener('input', toggle);

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const fd = new FormData(form);
        fetch("{{ route('admin.attendance.scan.store') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': fd.get('_token') },
            body: fd
        }).then(r => r.json()).then(data => {
            if (data.ok) {
                status.textContent = data.message;
                results.innerHTML = data.html;
                input.value = '';
                toggle();
                results.querySelectorAll('.ajax-page').forEach(a => {
                    a.addEventListener('click', ev => ev.preventDefault()); // can extend to load more
                });
            } else {
                status.textContent = data.message || 'Error';
            }
        }).catch(() => status.textContent = 'Network error');
    });
});
</script>
@endpush
