@extends('layouts.app')
<style>
.select2-container{
    width: 100% !important;
}
</style>
@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('payment.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.payments.index') }}" class="text-muted">
                <i class="fas fa-money-bill-wave mr-1"></i> {{ __('payment.titles.payments_list') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('payment.actions.create') }}</span>
        </li>
    </ul>
@endsection

@section('content')
 <div class="card card-custom">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $readonlyRoles = ['system_admin', 'branch_admin', 'academy_admin', 'coach', 'player'];
            $isDisabled = in_array(auth()->user()->role, $readonlyRoles);

            $selectedSystemId = old('system_id', $player->user->system_id ?? auth()->user()->system_id);
            $selectedBranchId = old('branch_id', $player->user->branch_id ?? auth()->user()->branch_id);

            $rawAcademyId = old('academy_id', auth()->user()->academy_id);
            if (is_array($rawAcademyId)) {
                $academyIds = array_map('intval', $rawAcademyId);
            } elseif (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
                $academyIds = array_map('intval', json_decode($rawAcademyId, true) ?? []);
            } elseif (!is_null($rawAcademyId)) {
                $academyIds = [(int) $rawAcademyId];
            } else {
                $academyIds = [];
            }

            $nameField = 'name_' . app()->getLocale();
        @endphp

        <form method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            <div class="row">
                @if($isDisabled)
                    <input type="hidden" name="system_id" value="{{ $selectedSystemId }}">
                @endif
                <div class="form-group col-md-4">
                    <label><i class="la la-network-wired mr-1"></i> {{ __('player.fields.system') }}</label>
                    <select name="system_id" id="system_id" class="form-control" {{ $isDisabled ? 'disabled' : '' }} required>
                        @foreach($systems as $system)
                            <option value="{{ $system->id }}" {{ (int) $selectedSystemId === (int) $system->id ? 'selected' : '' }}>
                                {{ $system->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($isDisabled)
                    <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                @endif
                <div class="form-group col-md-4">
                    <label><i class="la la-code-branch mr-1"></i> {{ __('player.fields.branch') }}</label>
                    <select name="branch_id" id="branch_id" class="form-control" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ (int) $selectedBranchId === (int) $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($isDisabled)
                    @foreach($academyIds as $id)
                        <input type="hidden" name="academy_id[]" value="{{ $id }}">
                    @endforeach
                @endif
                <div class="form-group col-md-4">
                    <label><i class="la la-university mr-1"></i> {{ __('player.fields.academy') }}</label>
                    <select name="academy_id" id="academy_id" class="form-control" required>
                        @foreach($academies as $academy)
                            <option value="{{ $academy->id }}" {{ in_array((int) $academy->id, $academyIds) ? 'selected' : '' }}>
                                {{ $academy->$nameField ?? $academy->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label><i class="la la-tags mr-1"></i> {{ __('payment.fields.category') }}</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="" disabled selected>{{ __('payment.filters.select') }}</option>
                        @foreach (\App\Models\Payment::CATEGORIES as $key => $value)
                            <option value="{{ $key }}">{{ __('payment.categories.' . $key) }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group col-md-4" id="program-dev" style="display:none;">
                    <label><i class="la la-cube mr-1"></i> {{ __('payment.fields.program') }}</label>
                    <select name="program_id" id="program_id" class="form-control"></select>
                </div>
                                <div class="form-group col-md-4" id="player-dev" style="display:none;">
                    <label><i class="la la-user mr-1"></i> {{ __('payment.fields.player') }}</label>
                    <select name="player_id" id="player_id" class="select2 form-control"></select>
                </div>

                <input type="number" name="program_price" id="program_price" class="form-control" value="0" hidden>
                <input type="text" name="program_currency" id="program_currency" class="form-control" value="AED" hidden>

                <div class="form-group col-md-12" id="classes-dev" style="display:none;">
                    <label><i class="la la-layer-group mr-1"></i> {{ __('payment.fields.classes') }}</label>
                    <select id="classes-select" class="form-control" multiple name="classes[]"></select>
                </div>

                <div class="form-group col-md-3" id="class-dev" style="display:none;">
                    <label><i class="la la-list-ol mr-1"></i> {{ __('payment.fields.class_count') }}</label>
                    <input type="number" name="class_count" class="form-control">
                </div>

                <div class="form-group col-md-3">
                    <label><i class="la la-dollar-sign mr-1"></i> {{ __('payment.fields.base_price') }}</label>
                    <input type="number" name="base_price" id="base_price" class="form-control" step="0.01" required>
                </div>

                <div class="form-group col-md-3">
                    <label><i class="la la-percentage mr-1"></i> {{ __('payment.fields.vat_percent') }}</label>
                    <input type="number" name="vat_percent" class="form-control" step="0.01">
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-file-invoice-dollar mr-1"></i> {{ __('payment.fields.is_vat_inclusive') }}</label>
                    <select name="is_vat_inclusive" class="form-control" required>
                        <option value="1">{{ __('payment.vat.inclusive') }}</option>
                        <option value="0">{{ __('payment.vat.exclusive') }}</option>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label><i class="la la-calculator mr-1"></i> {{ __('payment.fields.total_price') }}</label>
                    <input type="number" name="total_price" class="form-control" step="0.01" required>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-money-bill-wave mr-1"></i> {{ __('payment.fields.paid_amount') }}</label>
                    <input type="number" name="paid_amount" class="form-control" step="0.01" required>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-hand-holding-usd mr-1"></i> {{ __('payment.fields.remaining_amount') }}</label>
                    <input type="number" name="remaining_amount" class="form-control" step="0.01" readonly>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-coins mr-1"></i> {{ __('payment.fields.currency') }}</label>
                    <select name="currency" class="form-control" required>
                        <option value="" disabled selected>{{ __('payment.filters.select') }}</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-flag-checkered mr-1"></i> {{ __('payment.fields.status') }}</label>
                    <select name="status" class="form-control" required>
                        @foreach (['pending', 'partial', 'paid'] as $status)
                            <option value="{{ $status }}">{{ __('payment.status.' . $status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label><i class="la la-credit-card mr-1"></i> {{ __('payment.fields.payment_method') }}</label>
                    <select name="payment_method_id" class="form-control" required>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label><i class="la la-comment-alt mr-1"></i> {{ __('payment.fields.note') }}</label>
                    <textarea name="note" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label><i class="la la-boxes mr-1"></i> {{ __('payment.fields.items') }}</label>
                <table class="table table-bordered" id="items-table">
                    <thead>
                        <tr>
                            <th>{{ __('payment.fields.item') }}</th>
                            <th>{{ __('payment.fields.quantity') }}</th>
                            <th>{{ __('payment.fields.price') }}</th>
                            <th>{{ __('payment.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button type="button" class="btn btn-sm btn-primary" id="add-item">
                    <i class="la la-plus"></i> {{ __('payment.actions.add_item') }}
                </button>
            </div>
            <input type="hidden" name="items" id="items-json">
            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    <i class="la la-save mr-1"></i> {{ __('payment.actions.save') }}
                </button>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-left mr-1"></i> {{ __('payment.actions.back') }}
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let exchangeRates = {
    'USD_AED': 3.67,
    'AED_USD': 1/3.67,
    'EUR_AED': 4.0,
    'AED_EUR': 1/4.0
    // etc.
};
</script>

<script>
$(document).ready(function() {
    $('.select2').select2();
    let systemItems = [];
       const preselectedSystemId = $('#system_id').val();

    if (preselectedSystemId) {
        const systemId = $(this).val();


        if (preselectedSystemId) {

            $.get(`/admin/get-items-by-system/${preselectedSystemId}`, function(data) {
                systemItems = data;
            });
        } else {
            systemItems = [];
        }

    }

    function convertCurrency(amount, fromCurrency, toCurrency) {
        if (fromCurrency === toCurrency) return amount;
        const key = fromCurrency + '_' + toCurrency;
        const rate = exchangeRates[key];
        if (!rate) {
            console.warn(`Missing exchange rate for ${key}`);
            return amount;
        }
        return amount * rate;
    }

    function calculateBasePrice() {
        const targetCurrency = $('select[name="currency"]').val();
        let itemsTotal = 0;

        // Sum item prices (converted)
        $('#items-table tbody tr').each(function() {
            const selected = $(this).find('.item-select option:selected');
            const price = parseFloat(selected.data('price')) || 0;
            const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
            const currency = selected.data('currency') || 'AED';
            itemsTotal += convertCurrency(price, currency, targetCurrency) * quantity;
        });

        // Program price (converted)
        let programPrice = parseFloat($('#program_price').val()) || 0;
        const programCurrency = $('#program_currency').val() || 'AED';
        programPrice = convertCurrency(programPrice, programCurrency, targetCurrency);

        // Classes portion
        const classes = $('#classes-select').val() || [];
        let classPortion = 0;
        if (classes.length > 0) {
            classPortion = (programPrice / classes.length) * classes.length;
        }

        // Base Price = Items + Program + Classes
        const basePrice = itemsTotal + programPrice + classPortion;

        $('input[name="base_price"]').val(basePrice.toFixed(2));
        calculateTotals();
    }

    function calculateTotals() {
        const basePrice = parseFloat($('input[name="base_price"]').val()) || 0;
        const vatPercent = parseFloat($('input[name="vat_percent"]').val()) || 0;
        const paidAmount = parseFloat($('input[name="paid_amount"]').val()) || 0;
        const vatAmount = (basePrice * vatPercent) / 100;
        const totalPrice = basePrice + vatAmount;
        const remaining = totalPrice - paidAmount;

        $('input[name="total_price"]').val(totalPrice.toFixed(2));
        $('input[name="remaining_amount"]').val(remaining.toFixed(2));
    }

    $('#system_id').change(function() {
        const systemId = $(this).val();
        $('#branch_id, #academy_id, #player_id').empty();

        if (systemId) {
            $.get(`/admin/get-branches-by-system/${systemId}`, function(data) {
                $('#branch_id').append('<option value="">Select</option>');
                data.forEach(branch => {
                    $('#branch_id').append(`<option value="${branch.id}">${branch.name}</option>`);
                });
            });
            $.get(`/admin/get-items-by-system/${systemId}`, function(data) {
                systemItems = data;
            });
        } else {
            systemItems = [];
        }
    });

    $('#branch_id').change(function() {
        const branchId = $(this).val();
        $('#academy_id, #player_id').empty();
        if (branchId) {
            $.get(`/admin/get-academies-by-branch/${branchId}`, function(data) {
                $('#academy_id').append('<option value="">Select</option>');
                data.forEach(academy => {
                    $('#academy_id').append(`<option value="${academy.id}">${academy.name_en}</option>`);
                });
            });
        }
    });

    $('#program_id').change(function() {
        const programId = $(this).val();
        $('#classes-select').empty();

        const selected = $(this).find('option:selected');
        const price = parseFloat(selected.data('price')) || 0;
        const currency = selected.data('currency') || 'AED';
        $('#program_price').val(price.toFixed(2));
        $('#program_currency').val(currency);

        if (programId) {
            $.get(`/admin/get-classes-by-program/${programId}`, function(data) {
                data.forEach(cls => {
                    const label = `${cls.day} | ${cls.start_time}-${cls.end_time} | ${cls.location} | ${cls.coach_name}`;
                    $('#classes-select').append(`<option value="${cls.id}">${label}</option>`);
                });
                $('#classes-dev').fadeIn();
                calculateBasePrice();
            });
        } else {
            $('#classes-dev').fadeOut();
            calculateBasePrice();
        }
    });

    $('#academy_id, #category').change(function() {
        const academyId = $('#academy_id').val();
        const category = $('#category').val();
        $('#player_id').empty();
        $('#program_id').empty().append('<option value="">Select</option>');

        if (academyId) {
            $.get(`/admin/get-programs-by-academy/${academyId}`, function(data) {
                data.forEach(program => {
                    $('#program_id').append(
                        `<option value="${program.id}" data-currency="${program.currency}" data-price="${program.price}">
                            ${program.name_en} - ${program.price} ${program.currency}
                        </option>`
                    );
                });
            });
        }

       // helper: load players for a given program
function loadPlayersByProgram(programId) {
    const $player = $('#player_id');
    $player.empty().append('<option value="">Loading...</option>');

    if (!programId) {
        $player.empty().append('<option value="">Select</option>');
        return;
    }

    $.getJSON(`/admin/get-players-by-program/${programId}`)
        .done(function (data) {
            $player.empty().append('<option value="">Select</option>');
            data.forEach(function (player) {
                // expects {id, name} from the controller
               $player.append(`<option value="${player.id}">${player.id} - ${player.name}</option>`);

            });
        })
        .fail(function () {
            $player.empty().append('<option value="">Failed to load</option>');
        });
}

if (academyId && (category === 'program' || category === 'uniform' || category === 'class')) {
    $('#player-dev, #program-dev').fadeIn();
    $('#class-dev').toggle(category !== 'class');
    $('#classes-dev').toggle(category === 'class');

    // load players whenever program changes
    $('#program_id').off('change.loadPlayers').on('change.loadPlayers', function () {
        const programId = $(this).val();
        loadPlayersByProgram(programId);
    });

    // if a program is already selected (e.g., after validation), load immediately
    const initialProgramId = $('#program_id').val();
    loadPlayersByProgram(initialProgramId);
} else {
    $('#player-dev, #program-dev, #class-dev, #classes-dev').fadeOut();
    $('#player_id').empty().append('<option value="">Select</option>');
}

        calculateBasePrice();
    });

    $('#add-item').click(function() {

        if (systemItems.length === 0 && !preselectedSystemId ) {
            alert('Please select a system first.');
            return;
        }

        const rowId = Date.now();
        let options = '<option value="">Select</option>';
        systemItems.forEach(item => {
            options += `<option value="${item.id}" data-price="${item.price}" data-currency="${item.currency ?? 'AED'}">${item.name_en}</option>`;
        });
        $('#items-table tbody').append(`
<tr data-row-id="${rowId}">
    <td><select class="form-control item-select">${options}</select></td>
    <td><input type="number" class="form-control quantity-input" min="1" value="1"></td>
    <td class="item-price-cell">-</td>
    <td><button type="button" class="btn btn-sm remove-item"><i class="la la-trash"></i></button></td>
</tr>`);
    });

    $(document).on('change', '.item-select', function() {
         const selected = $(this).find('option:selected');
    const price = parseFloat(selected.data('price')) || 0;
    const currency = selected.data('currency') || 'AED';
    $(this).closest('tr').find('.item-price-cell').text(`${price.toFixed(2)} ${currency}`);
    calculateBasePrice();
    });

    $(document).on('input', '.quantity-input', calculateBasePrice);

    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        calculateBasePrice();
    });

    //$('#classes-select').change(calculateBasePrice);
    $('select[name="currency"]').change(calculateBasePrice);
    $('input[name="vat_percent"], input[name="paid_amount"]').on('input', calculateTotals);

    $('form').submit(function() {
        const items = [];
        $('#items-table tbody tr').each(function() {
            const selected = $(this).find('.item-select option:selected');
            const itemId = $(this).find('.item-select').val();
            const quantity = $(this).find('.quantity-input').val();
            const price = selected.data('price') || 0;
            const currency = selected.data('currency') || 'AED';
            if (itemId && quantity) {
                items.push({ item_id: itemId, quantity: parseInt(quantity), price: parseFloat(price), currency });
            }
        });
        $('#items-json').val(JSON.stringify(items));
        const classes = $('#classes-select').val() || [];
        $('#selected-classes').val(JSON.stringify(classes));
    });

    calculateBasePrice();
});
</script>
