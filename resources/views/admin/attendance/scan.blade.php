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

            <div id="scanSummary"></div>
<h6 class="mb-3">{{ __('attendance.titles.view') }}</h6>
<div id="scanResults"></div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const summary = document.getElementById('scanSummary');
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

    // SweetAlert-powered delete + pagination rebinder
    function bindResultEvents(){
        // pagination (kept simple)
        results.querySelectorAll('.ajax-page').forEach(a => {
            a.addEventListener('click', ev => ev.preventDefault());
        });

        // DELETE with SweetAlert2
        results.querySelectorAll('.ajax-delete').forEach(btn => {
            btn.addEventListener('click', ev => {
                ev.preventDefault();
                const url = btn.dataset.url;
                if (!url) return;

                Swal.fire({
                    title: "{{ __('attendance.confirm_delete') ?? 'Delete this record?' }}",
                    text: "{{ __('attendance.delete_warning') ?? 'This action cannot be undone.' }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('attendance.delete') ?? 'Delete' }}",
                    cancelButtonText: "{{ __('attendance.cancel') ?? 'Cancel' }}",
                    confirmButtonColor: '#e3342f'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const fd = new FormData();
                        fd.set('_token', form.querySelector('input[name=_token]').value);
                        fd.set('_method', 'DELETE');

                        fetch(url, { method: 'POST', body: fd })
                          .then(r => {
                              if (!r.ok) throw new Error('Network response was not ok');
                              // Optimistically remove row from DOM
                              const row = btn.closest('tr');
                              if (row) row.remove();

                              Swal.fire({
                                  toast: true, position: 'top-end', timer: 2000, showConfirmButton: false,
                                  icon: 'success',
                                  title: "{{ __('attendance.deleted') ?? 'Deleted' }}"
                              });
                          })
                          .catch(() => {
                              Swal.fire({
                                  icon: 'error',
                                  title: "{{ __('attendance.delete_failed') ?? 'Delete failed' }}",
                                  text: "{{ __('attendance.try_again') ?? 'Please try again.' }}"
                              });
                          });
                    }
                });
            });
        });
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
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                status.textContent = data.message || 'Saved';
                if (data.summary_html) summary.innerHTML = data.summary_html;
                results.innerHTML = data.html;
                bindResultEvents(); // <-- bind after injecting partial
                input.value = '';
                toggle();
            } else {
                status.textContent = data.message || 'Error';
            }
        })
        .catch(() => status.textContent = 'Network error');
    });

    // initial state
    toggle();
});
</script>
@endpush

