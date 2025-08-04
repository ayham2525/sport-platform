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
            <div id="nfcStatus" class="mb-4 text-info">
                {{ isset($serial_number) ? __('card.card_already_scanned') : __('card.waiting_to_start') }}
            </div>

            <form method="POST" action="{{ route('admin.cards.store') }}">
                @csrf
                <input type="hidden" name="player_id" value="{{ $player->id }}">

                <div class="form-group">
                    <label>
                        <i class="la la-barcode"></i>
                        {{ __('card.card_serial_number') }}
                    </label>
                    <input type="text" name="serial_number" id="serial_number"
                        class="form-control"
                        value="{{ $serial_number ?? '' }}"
                        readonly required>
                </div>

                <button type="button" class="btn btn-outline-info " id="startScanBtn">
                    <i class="la la-search"></i> {{ __('card.scan_card') }}
                </button>

                <button type="submit" class="btn btn-primary" id="saveBtn" {{ isset($serial_number) ? '' : 'disabled' }}>
                    <i class="la la-save"></i> {{ __('card.save') }}
                </button>

                <a href="{{ route('admin.players.index') }}" class="btn btn-secondary">
                    <i class="la la-times"></i> {{ __('card.cancel') }}
                </a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    const socket = io('http://localhost:3001', { autoConnect: false }); // start disconnected
    const serialInput = document.getElementById("serial_number");
    const statusDiv = document.getElementById("nfcStatus");
    const saveBtn = document.getElementById("saveBtn");
    const startScanBtn = document.getElementById("startScanBtn");

    startScanBtn.addEventListener('click', () => {
        statusDiv.innerText = "🔄 {{ __('card.connecting') }}";
        socket.connect();
    });

    socket.on('connect', () => {
        statusDiv.innerText = '✅ {{ __('card.connected_wait') }}';
    });

    socket.on('card-read', (serial) => {
        serialInput.value = serial;
        saveBtn.disabled = false;
        statusDiv.innerText = `✅ {{ __('card.card_scanned') }}: ${serial}`;
        socket.disconnect();
    });

    socket.on('card-removed', () => {
        serialInput.value = '';
        saveBtn.disabled = true;
        statusDiv.innerText = '🟡 {{ __('card.card_removed') }}';
    });

    socket.on('disconnect', () => {
        if (!serialInput.value) {
            statusDiv.innerText = '🟠 {{ __('card.reader_disconnected') }}';
        }
    });
</script>
@endsection
