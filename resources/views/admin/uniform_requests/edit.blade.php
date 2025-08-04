@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('uniform_requests.edit_title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home"></i> {{ __('uniform_requests.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.uniform-requests.index') }}" class="text-muted">
            <i class="la la-tshirt"></i> {{ __('uniform_requests.title') }}
        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <i class="la la-edit"></i> {{ __('uniform_requests.edit_title') }}
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title"><i class="la la-tshirt"></i> {{ __('uniform_requests.edit_title') }}</h3>
        </div>

        <form action="{{ route('admin.uniform-requests.update', $uniformRequest->id) }}" method="POST" class="card-body">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="pl-3 mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                {{-- System --}}
                <div class="form-group col-md-6">
                    <label><i class="la la-network-wired text-muted mr-1"></i> {{ __('uniform_requests.fields.system') }}</label>

                    {{-- Hidden input to send system_id --}}
                    <input type="hidden" name="system_id" value="{{ $uniformRequest->system_id }}">

                    {{-- Disabled select for display --}}
                    <select id="system_id" class="form-control" disabled>
                        <option value="">{{ __('uniform_requests.select_system') }}</option>
                        @foreach($systems as $id => $name)
                        <option value="{{ $id }}" {{ $uniformRequest->system_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Branch --}}
                <div class="form-group col-md-6">
                    <label><i class="la la-code-branch text-muted mr-1"></i> {{ __('uniform_requests.fields.branch') }}</label>

                    {{-- Hidden input to send branch_id --}}
                    <input type="hidden" name="branch_id" value="{{ $uniformRequest->branch_id }}">

                    {{-- Disabled select for display --}}
                    <select id="branch_id" class="form-control" disabled>
                        <option value="">{{ __('uniform_requests.select_branch') }}</option>
                        @foreach($branches as $id => $name)
                        <option value="{{ $id }}" {{ $uniformRequest->branch_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Player --}}
                <div class="form-group col-md-6">
                    <label><i class="la la-user text-muted mr-1"></i> {{ __('uniform_requests.fields.player') }}</label>

                    {{-- Hidden input to send player_id --}}
                    <input type="hidden" name="player_id" value="{{ $uniformRequest->player_id }}">

                    {{-- Disabled select for display --}}
                    <select id="player_id" class="form-control" disabled>
                        <option value="">{{ __('uniform_requests.select_player') }}</option>
                        @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ $uniformRequest->player_id == $player->id ? 'selected' : '' }}>
                            {{ $player->user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-tasks text-muted mr-1"></i> {{ __('uniform_requests.fields.status') }}</label>
                    <select name="status" class="form-control" required>
                        <option value="">{{ __('uniform_requests.select_status') }}</option>
                        @foreach (\App\Models\UniformRequest::STATUS_OPTIONS as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $uniformRequest->status) === $key ? 'selected' : '' }}>
                            {{ __('uniform_requests.statuses.' . $key) }}
                        </option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group col-md-6">
                    <label><i class="la la-box text-muted mr-1"></i> {{ __('uniform_requests.fields.item') }}</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">{{ __('uniform_requests.select_item') }}</option>
                        @if(!empty($items) && is_iterable($items))
                        @foreach($items as $id => $name)
                        <option value="{{ $id }}" {{ $uniformRequest->item_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-ruler-combined text-muted mr-1"></i> {{ __('uniform_requests.fields.size') }}</label>
                    <select name="size" class="form-control" required>
                        <option value="">{{ __('uniform_requests.select_size') }}</option>
                        @foreach(__('uniform_requests.sizes') as $key => $label)
                        <option value="{{ $key }}" {{ old('size', $uniformRequest->size ?? '') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-palette text-muted mr-1"></i> {{ __('uniform_requests.fields.color') }}</label>
                    <input type="color" name="color" class="form-control" value="{{ $uniformRequest->color ?? '#D71920' }}">
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-sort-numeric-up text-muted mr-1"></i> {{ __('uniform_requests.fields.quantity') }}</label>
                    <input type="number" name="quantity" class="form-control" min="1" value="{{ $uniformRequest->quantity }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-money-bill-wave text-muted mr-1"></i> {{ __('uniform_requests.fields.amount') }}</label>
                    <input type="number" name="amount" step="0.01" class="form-control" value="{{ $uniformRequest->amount }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-dollar-sign text-muted mr-1"></i> {{ __('uniform_requests.fields.currency') }}</label>
                    <select name="currency_id" class="form-control" required>
                        @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ $uniformRequest->currency_id == $currency->id ? 'selected' : '' }}>
                            {{ $currency->code }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label><i class="la la-sticky-note text-muted mr-1"></i> {{ __('uniform_requests.fields.notes') }}</label>
                    <textarea name="notes" rows="3" class="form-control">{{ $uniformRequest->notes }}</textarea>
                </div>
                @php
    $user = Auth::user();
@endphp

@if (in_array($user->role, ['full_admin', 'system_admin']))

        {{-- Admin Remarks --}}
        <div class="form-group col-md-12">
            <label><i class="la la-comment-dots text-muted mr-1"></i> {{ __('uniform_requests.fields.admin_remarks') }}</label>
            <textarea name="admin_remarks" rows="3" class="form-control">{{ old('admin_remarks', $uniformRequest->admin_remarks) }}</textarea>
        </div>

        {{-- Approved At --}}
        <div class="form-group col-md-4">
            <label><i class="la la-check-circle text-muted mr-1"></i> {{ __('uniform_requests.fields.approved_at') }}</label>
            <input type="datetime-local" name="approved_at" class="form-control"
                   value="{{ old('approved_at', optional($uniformRequest->approved_at)->format('Y-m-d\TH:i')) }}">
        </div>

        {{-- Ordered At --}}
        <div class="form-group col-md-4">
            <label><i class="la la-shipping-fast text-muted mr-1"></i> {{ __('uniform_requests.fields.ordered_at') }}</label>
            <input type="datetime-local" name="ordered_at" class="form-control"
                   value="{{ old('ordered_at', optional($uniformRequest->ordered_at)->format('Y-m-d\TH:i')) }}">
        </div>

        {{-- Delivered At --}}
        <div class="form-group col-md-4">
            <label><i class="la la-box-open text-muted mr-1"></i> {{ __('uniform_requests.fields.delivered_at') }}</label>
            <input type="datetime-local" name="delivered_at" class="form-control"
                   value="{{ old('delivered_at', optional($uniformRequest->delivered_at)->format('Y-m-d\TH:i')) }}">
        </div>

@endif
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="la la-save"></i> {{ __('uniform_requests.actions.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#system_id').on('change', function() {
            const systemId = $(this).val();
            $('#branch_id, #player_id, #item_id').html('');

            if (systemId) {
                $.get(`/admin/get-branches-by-system/${systemId}`, function(branches) {
                    $('#branch_id').append(`<option value="">${@json(__('uniform_requests.select_branch'))}</option>`);
                    branches.forEach(branch => {
                        $('#branch_id').append(`<option value="${branch.id}">${branch.name}</option>`);
                    });
                });

                $.get(`/admin/get-items-by-system/${systemId}`, function(items) {
                    $('#item_id').append(`<option value="">${@json(__('uniform_requests.select_item'))}</option>`);
                    items.forEach(item => {
                        $('#item_id').append(`<option value="${item.id}">${item.name_en}</option>`);
                    });
                });
            }
        });

        $('#branch_id').on('change', function() {
            const branchId = $(this).val();
            $('#player_id').html(`<option value="">${@json(__('uniform_requests.select_player'))}</option>`);

            if (branchId) {
                $.get(`/admin/get-players-by-branch/${branchId}`, function(players) {
                    players.forEach(player => {
                        if (player.user) {
                            $('#player_id').append(`<option value="${player.id}">${player.user.name}</option>`);
                        }
                    });
                });
            }
        });
    });

</script>
@endpush

