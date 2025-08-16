@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">
    {{ __('card.scan_card_for') }} {{ $player->user->name }}
</h5>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header d-flex flex-column">
            <h3 class="card-title">
                <i class="la la-credit-card text-primary"></i>
                {{ __('card.tap_card') }}
            </h3>
            <h5 class="card-title text-muted">
                <i class="la la-user"></i>
                {{ __('card.user_id') }} : {{ $player->user->id }}
            </h5>
        </div>

        <div class="card-body">
            {{-- ✅ Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="la la-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- ❌ Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="la la-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            {{-- ❌ Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="la la-times-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="nfcStatus" class="mb-3 text-info">
                {{ __('card.waiting_to_start') }}
            </div>

            <form method="POST" action="{{ route('admin.cards.store') }}" id="scanForm">
                @csrf
                <input type="hidden" name="player_id" value="{{ $player->id }}">

                <div class="form-group">
                    <label class="d-flex align-items-center">
                        <i class="la la-barcode mr-2"></i>
                        <span>{{ __('card.card_serial_number') }}</span>
                    </label>
                    <input
                        type="text"
                        name="card_serial_number"
                        id="card_serial_number"
                        class="form-control text-center @error('card_serial_number') is-invalid @enderror"
                        autofocus
                        required
                        autocomplete="off"
                        inputmode="numeric"
                        placeholder="{{ __('card.tap_now') }}"
                        value="{{ $card_serial_number }}"
                    >
                    {{-- Inline validation error --}}
                    @error('card_serial_number')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-flex flex-wrap align-items-center">
                    <button type="submit" class="btn btn-primary mr-2 mb-2" id="saveBtn" disabled>
                        <i class="la la-save"></i> {{ __('card.save') }}
                    </button>

                    <a href="{{ route('admin.players.index') }}" class="btn btn-secondary mb-2">
                        <i class="la la-times"></i> {{ __('card.cancel') }}
                    </a>
                </div>
            </form>

            <small class="text-muted d-block mt-3">
                {{ __('card.note') ?? 'Note:' }}
                {{ __('card.nfc_hint') ?? 'Place the cursor in the input field and tap the card. The reader will type the number automatically.' }}
            </small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input   = document.getElementById('card_serial_number'); // ✅ fixed ID
    const status  = document.getElementById('nfcStatus');
    const saveBtn = document.getElementById('saveBtn');

    if (input) {
        input.focus();

        // Prevent Enter from submitting the form (reader sends Enter)
        input.addEventListener('keydown', (e) => {
            if (e.key === "Enter") {
                e.preventDefault(); // stop reload
                if (input.value.trim().length > 0) {
                    status.textContent = "✅ {{ __('card.card_scanned') }}: " + input.value;
                    saveBtn.disabled = false; // enable save button
                }
            }
        });

        // Update status when card UID is typed in
        input.addEventListener('input', () => {
            if (input.value.trim().length > 0) {
                status.textContent = "✅ {{ __('card.card_scanned') }}: " + input.value;
                saveBtn.disabled = false;
            } else {
                status.textContent = "⏳ {{ __('card.waiting_to_start') }}";
                saveBtn.disabled = true;
            }
        });
    }
});
</script>
@endpush
