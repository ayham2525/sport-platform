@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('uniform_requests.create_title') }}</h5>
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
        <i class="la la-plus-circle"></i> {{ __('uniform_requests.create_title') }}
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title"><i class="la la-tshirt"></i> {{ __('uniform_requests.new') }}</h3>
        </div>

        <form action="{{ route('admin.uniform-requests.store') }}" method="POST" class="card-body">
            @csrf

            {{-- Display Validation Errors --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <h6 class="mb-1"><i class="la la-exclamation-triangle"></i> {{ __('There were some problems with your
                    input:') }}</h6>
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">

                <div class="form-group col-md-6">
                    <label><i class="la la-network-wired text-muted mr-1"></i> {{ __('uniform_requests.fields.system')
                        }}</label>
                    <select name="system_id" id="system_id" class="form-control" required>
                        @if (auth()->user()->role === 'full_admin')
                        <option value="">{{ __('uniform_requests.select_system') }}</option>
                        @endif
                        @foreach ($systems as $id => $name)
                        <option value="{{ $id }}" {{ request('system_id')==$id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group col-md-6">
                    <label><i class="la la-code-branch text-muted mr-1"></i> {{ __('uniform_requests.fields.branch')
                        }}</label>
                    <select name="branch_id" id="branch_id" class="form-control" required>
                        @if(in_array(Auth::user()->role, ['full_admin' ,'system_admin']) )
                        <option value="">{{ __('uniform_requests.select_branch') }}</option>
                        @endif
                        @foreach ($branches as $id => $name)
                        <option value="{{ $id }}" {{ request('branch_id')==$id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-user text-muted mr-1"></i> {{ __('uniform_requests.fields.player') }}</label>
                    <select name="player_id" id="player_id" class="form-control select2" required>
                        <option value="">{{ __('uniform_requests.select_player') }}</option>

                        @foreach ($players as $player)
                        @if ($player->user)
                        <option value="{{ $player->id }}" {{ request('player_id')==$player->id ? 'selected' : '' }}>
                            {{ $player->id }} - {{ $player->user->name }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label><i class="la la-tasks text-muted mr-1"></i> {{ __('uniform_requests.fields.status')
                        }}</label>
                    <select name="status" class="form-control" required>
                        <option value="">{{ __('uniform_requests.select_status') }}</option>
                        @foreach (\App\Models\UniformRequest::STATUS_OPTIONS as $key => $label)
                        <option value="{{ $key }}" {{ old('status')==$key ? 'selected' : '' }}>
                            {{ __('uniform_requests.statuses.' . $key) }}
                        </option>
                        @endforeach
                    </select>
                </div>




                <div class="form-group col-md-6">
                    <label><i class="la la-box text-muted mr-1"></i> {{ __('uniform_requests.fields.item') }}</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">{{ __('uniform_requests.select_item') }}</option>
                        @foreach ($items as $id => $name)
                        <option value="{{ $id }}" {{ request('item_id')==$id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-ruler-combined text-muted mr-1"></i> {{ __('uniform_requests.fields.size')
                        }}</label>
                    <select name="size" class="form-control" required>
                        <option value="">{{ __('uniform_requests.select_size') }}</option>
                        @foreach(__('uniform_requests.sizes') as $key => $label)
                        <option value="{{ $key }}" {{ old('size', $uniformRequest->size ?? '') === $key ? 'selected' :
                            '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-palette text-muted mr-1"></i> {{ __('uniform_requests.fields.color')
                        }}</label>
                    <input type="color" name="color" class="form-control" value="#D71920">
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-sort-numeric-up text-muted mr-1"></i> {{
                        __('uniform_requests.fields.quantity') }}</label>
                    <input type="number" name="quantity" class="form-control" value="1" required>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-money-bill-wave text-muted mr-1"></i> {{ __('uniform_requests.fields.amount')
                        }}</label>
                    <input type="number" name="amount" step="0.01" class="form-control" required>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-dollar-sign text-muted mr-1"></i> {{ __('uniform_requests.fields.currency')
                        }}</label>

                    {{-- Hidden input to actually send the currency_id value --}}
                    <input type="hidden" name="currency_id" value="{{ optional($currencies->firstWhere('code', 'AED'))->id }}">

                    {{-- Disabled select for display only --}}
                    <select class="form-control" disabled>
                        @foreach ($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ $currency->code === 'AED' ? 'selected' : '' }}>
                            {{ $currency->code }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group col-md-12">
                    <label><i class="la la-sticky-note text-muted mr-1"></i> {{ __('uniform_requests.fields.notes')
                        }}</label>
                    <textarea name="notes" rows="3" class="form-control"></textarea>
                </div>
                {{-- Branch Status --}}
                <div class="form-group col-md-4">
                    <label><i class="la la-sitemap text-muted mr-1"></i> {{ __('uniform_requests.fields.branch_status') }}</label>
                    <select name="branch_status" class="form-control">
                        <option value="">{{ __('uniform_requests.select_branch_status') }}</option>
                        @foreach (\App\Models\UniformRequest::BRANCH_STATUS_OPTIONS as $key => $label)
                        <option value="{{ $key }}" {{ old('branch_status') === $key ? 'selected' : '' }}>
                            {{ __('uniform_requests.branch_statuses.' . $key) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Office Status (admins only) --}}
               @php $user = Auth::user(); @endphp
@if (in_array($user->role, ['full_admin', 'system_admin']))
    <div class="form-group col-md-4">
        <label><i class="la la-building text-muted mr-1"></i> {{ __('uniform_requests.fields.office_status') }}</label>
        <select name="office_status" class="form-control">
            <option value="">{{ __('uniform_requests.select_office_status') }}</option>
            @foreach (\App\Models\UniformRequest::OFFICE_STATUS_OPTIONS as $key => $label)
                <option value="{{ $key }}" {{ old('office_status') === $key ? 'selected' : '' }}>
                    {{ __('uniform_requests.office_statuses.' . $key) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- NEW: Stock Status --}}
    <div class="form-group col-md-4">
        <label><i class="la la-warehouse text-muted mr-1"></i> {{ __('uniform_requests.fields.stock_status') }}</label>
        <select name="stock_status" class="form-control">
            <option value="">{{ __('uniform_requests.select_stock_status') }}</option>
            @foreach (\App\Models\UniformRequest::STOCK_STATUS_OPTIONS as $key => $label)
                <option value="{{ $key }}" {{ old('stock_status') === $key ? 'selected' : '' }}>
                    {{ __('uniform_requests.stock_statuses.' . $key) }}
                </option>
            @endforeach
        </select>
    </div>
@endif


                {{-- Payment Method (varchar) --}}
                <div class="form-group col-md-4">
                    <label><i class="la la-credit-card text-muted mr-1"></i> {{ __('uniform_requests.fields.payment_method') }}</label>
                    <select name="payment_method" class="form-control">
                        <option value="">{{ __('uniform_requests.select_payment_method') }}</option>
                        @foreach($paymentMethods as $pm)
                        @php
                        $label = app()->getLocale() === 'ar'
                        ? ($pm->name_ar ?? $pm->name)
                        : (app()->getLocale() === 'ur' ? ($pm->name_ur ?? $pm->name) : $pm->name);
                        @endphp
                        <option value="{{ $label }}" {{ old('payment_method') === $label ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
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
        $('.select2').select2();

        // On system change → load branches and items
        $('#system_id').on('change', function() {
            const systemId = $(this).val();

            $('#branch_id').html(`<option value="">${@json(__('uniform_requests.select_branch'))}</option>`);
            $('#player_id').html(`<option value="">${@json(__('uniform_requests.select_player'))}</option>`);
            $('#item_id').html(`<option value="">${@json(__('uniform_requests.select_item'))}</option>`);

            if (systemId) {
                // Get branches
                $.get(`/admin/get-branches-by-system/${systemId}`, function(branches) {
                    branches.forEach(function(branch) {
                        $('#branch_id').append(
                            `<option value="${branch.id}">${branch.name}</option>`);
                    });
                });

                // Get items
                $.get(`/admin/get-items-by-system/${systemId}`, function(items) {
                    items.forEach(function(item) {
                        $('#item_id').append(
                            `<option value="${item.id}">${item.name_en}</option>`);
                    });
                });
            }
        });

        // On branch change → get players
        $('#branch_id').on('change', function() {
            const branchId = $(this).val();

            $('#player_id').html(`<option value="">${@json(__('uniform_requests.select_player'))}</option>`);

            if (branchId) {
                $.get(`/admin/get-players-by-branch/${branchId}`, function(players) {
                    players.forEach(function(player) {
                        if (player.user) {
                            $('#player_id').append(
                                `<option value="${player.id}">${player.user.name}</option>`
                            );
                        }
                    });
                });
            }
        });

    });

</script>
@endpush

