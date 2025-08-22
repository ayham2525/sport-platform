@extends('layouts.app')

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
            <span class="text-muted">{{ __('payment.actions.edit') }}</span>
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
        <form method="POST" action="{{ route('admin.payments.update', $payment->id) }}">
            @csrf
            @method('PUT')

            <input name="items" type="hidden"  id="items-json" value="{{ $payment->items }}">
            <input type="hidden" id="selected-classes" name="classes">
            <div class="row">
                <div class="form-group col-md-4">
                    <label><i class="la la-network-wired text-muted mr-1"></i> {{ __('payment.filters.select_system') }}</label>
                    <select class="form-control" required disabled>
                        @foreach ($systems as $system)
                            <option value="{{ $system->id }}" {{ $payment->system_id == $system->id ? 'selected' : '' }}>{{ $system->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" id="system_id" name="system_id" value="{{ $payment->system_id }}">
                </div>
                <div class="form-group col-md-4">
                    <label><i class="la la-building text-muted mr-1"></i> {{ __('payment.filters.select_branch') }}</label>
                    <select name="branch_id" id="branch_id" class="form-control" disabled>
                        @if ($payment->branch)
                            <option value="{{ $payment->branch_id }}" selected>{{ $payment->branch->name }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="branch_id" name="branch_id" value="{{ $payment->branch_id }}">
                </div>
                <div class="form-group col-md-4">
                    <label><i class="la la-university text-muted mr-1"></i> {{ __('payment.filters.select_academy') }}</label>
                    <select name="academy_id" id="academy_id" class="form-control" disabled>
                        @if ($payment->academy)
                            <option value="{{ $payment->academy_id }}" selected>{{ $payment->academy->name_en }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="academy_id" name="academy_id" value="{{ $payment->academy_id }}">
                </div>
                <div class="form-group col-md-4">
                    <label><i class="la la-list text-muted mr-1"></i> {{ __('payment.fields.category') }}</label>
                    <select class="form-control" required disabled>
                        @foreach (\App\Models\Payment::CATEGORIES as $key => $value)
                            <option value="{{ $key }}" {{ $payment->category == $key ? 'selected' : '' }}>{{ __('payment.categories.' . $key) }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="category" value="{{ $payment->category }}">
                </div>
                <div class="form-group col-md-4" id="player-dev" style="display:none;">
                    <label><i class="la la-user text-muted mr-1"></i> {{ __('payment.fields.player') }}</label>
                    <select class="form-control" disabled data-selected="{{ $payment->player_id }}"></select>
                    <input type="hidden" id="player_id" name="player_id" value="{{ $payment->player_id }}">
                </div>
                <div class="form-group col-md-4" id="program-dev" style="display:none;">
                    <label><i class="la la-cube text-muted mr-1"></i> {{ __('payment.fields.program') }}</label>
                    <select name="program_id" id="program_id" class="form-control" disabled>
                        @if ($payment->program)
                            <option value="{{ $payment->program_id }}" selected>{{ $payment->program->name_en }}</option>
                        @endif
                    </select>
                    <input type="hidden" id="program_id" name="program_id" value="{{ $payment->program_id }}">
                </div>
                <div class="form-group col-md-12" id="classes-dev" style="display:none;">
                    <label><i class="la la-list-alt text-muted mr-1"></i> {{ __('payment.fields.classes') }}</label>
                    <select id="classes-select" class="form-control" multiple name="classes[]" disabled></select>
                </div>
                <div class="form-group col-md-3" id="class-dev" style="display:none;">
                    <label><i class="la la-calculator text-muted mr-1"></i> {{ __('payment.fields.class_count') }}</label>
                    <input type="number" name="class_count" class="form-control" disabled value="{{ $payment->class_count }}">
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-money text-muted mr-1"></i> {{ __('payment.fields.base_price') }}</label>
                    <input type="number" name="base_price" class="form-control" step="0.01" value="{{ $payment->base_price }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-percent text-muted mr-1"></i> {{ __('payment.fields.vat_percent') }}</label>
                    <input type="number" name="vat_percent" class="form-control" step="0.01" value="{{ $payment->vat_percent }}">
                </div>
                <div class="form-group col-md-3">
                <label><i class="la la-file-invoice-dollar text-muted mr-1"></i> {{ __('payment.fields.is_vat_inclusive') }}</label>
                <select name="is_vat_inclusive" class="form-control" required>
                    <option value="1" {{ $payment->is_vat_inclusive ? 'selected' : '' }}>
                        {{ __('payment.vat.inclusive') }}
                    </option>
                    <option value="0" {{ !$payment->is_vat_inclusive ? 'selected' : '' }}>
                        {{ __('payment.vat.exclusive') }}
                    </option>
                </select>
            </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-calculator text-muted mr-1"></i> {{ __('payment.fields.total_price') }}</label>
                    <input type="number" name="total_price" class="form-control" step="0.01" value="{{ $payment->total_price }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-money-bill text-muted mr-1"></i> {{ __('payment.fields.paid_amount') }}</label>
                    <input type="number" name="paid_amount" class="form-control" step="0.01" value="{{ $payment->paid_amount }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-balance-scale text-muted mr-1"></i> {{ __('payment.fields.remaining_amount') }}</label>
                    <input type="number" name="remaining_amount" class="form-control" step="0.01" value="{{ $payment->remaining_amount }}" readonly>
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-coins text-muted mr-1"></i> {{ __('payment.fields.currency') }}</label>
                    <select class="form-control" required disabled>
                        <option value="" disabled {{ empty($payment->currency) ? 'selected' : '' }}>{{ __('payment.filters.select') }}</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->code }}" {{ $payment->currency == $currency->code ? 'selected' : '' }}>{{ $currency->code }}</option>
                        @endforeach
                    </select>
                    <input id="currency" name="currency" hidden value="{{ $payment->currency }}">
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-flag text-muted mr-1"></i> {{ __('payment.fields.status') }}</label>
                    <select name="status" class="form-control" required>
                        @foreach (['pending', 'partial', 'paid'] as $status)
                            <option value="{{ $status }}" {{ $payment->status == $status ? 'selected' : '' }}>{{ __('payment.status.' . $status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label><i class="la la-credit-card text-muted mr-1"></i> {{ __('payment.fields.payment_method') }}</label>
                    <select name="payment_method_id" class="form-control" required>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}" {{ $payment->payment_method_id == $method->id ? 'selected' : '' }}>{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label><i class="la la-sticky-note text-muted mr-1"></i> {{ __('payment.fields.note') }}</label>
                    <textarea name="note" class="form-control" rows="3">{{ $payment->note }}</textarea>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label><i class="la la-boxes text-muted mr-1"></i> {{ __('payment.fields.items') }}</label>
                <table class="table table-bordered" id="items-table">
                    <thead>
                        <tr>
                            <th>{{ __('payment.fields.item') }}</th>
                            <th>{{ __('payment.fields.quantity') }}</th>
                            <th>{{ __('payment.fields.price') }}</th>
                            <th>{{ __('payment.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-primary" id="add-item">
                    <i class="la la-plus"></i> {{ __('payment.actions.add_item') }}
                </button>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    <i class="la la-save mr-1"></i> {{ __('payment.actions.update') }}
                </button>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                    {{ __('payment.actions.back') }}
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
    $(document).ready(function() {
        const existingItems = JSON.parse($('#items-json').val() || '[]');

        const selectedClasses = @json($selectedClasses);
        let systemItems = [];


        const systemId = $('#system_id').val();
        if (systemId) {
            // Load branches
            $.get(`/admin/get-branches-by-system/${systemId}`, function(data) {
                $('#branch_id').append('<option value="">Select</option>');
                data.forEach(branch => {
                    const selected = branch.id == "{{ $payment->branch_id }}" ? 'selected' :
                    '';
                    $('#branch_id').append(
                        `<option value="${branch.id}" ${selected}>${branch.name}</option>`);
                });
            });

            // Load system items
            $.get(`/admin/get-items-by-system/${systemId}`, function(data) {
                systemItems = data;
                fillExistingItems(); // only now call fillExistingItems
            });

        }

       function fillExistingItems() {
    if (!systemItems.length) {
        // Retry after a short delay if systemItems not loaded yet
        setTimeout(fillExistingItems, 100);
        return;
    }

    $('#items-table tbody').empty();


    existingItems.forEach(item => {
        const rowId = Date.now() + Math.floor(Math.random() * 1000);

        // Build <select> element
        const select = $('<select>', { class: 'form-control item-select' });
        select.append('<option value="">Select</option>');

        systemItems.forEach(sysItem => {
            select.append(
                $('<option>', {
                    value: sysItem.id,
                    text: sysItem.name_en,
                    selected: String(sysItem.id) === String(item.item_id)
                })
            );
        });

        // Compose price + currency text

      const priceText = item.price && item.currency ? `${parseFloat(item.price).toFixed(2)} ${item.currency}` : '';

        //console.log(priceText);


        const row = $(`
            <tr data-row-id="${rowId}">
                <td></td>
                <td><input type="number" class="form-control quantity-input" min="1" value="${item.quantity}"></td>
                <td class="item-price-cell">${parseFloat(item.price).toFixed(2)} ${item.currency}</td>
                <td><button type="button" class="btn btn-sm remove-item"><i class="la la-trash"></i></button></td>
            </tr>
        `);

        row.find('td').first().append(select);
        $('#items-table tbody').append(row);
    });
}

  $(document).on('change', '.item-select', function() {
    const selectedOption = $(this).find('option:selected');

    const price = selectedOption.data('price') || 0;
    const currency = selectedOption.data('currency') || 'AED';
    const priceText = `${parseFloat(price).toFixed(2)} ${currency}`;

    $(this).closest('tr').find('.item-price-cell').text(priceText);
     calculateBasePrice();
});

    function calculateBasePrice() {
        const targetCurrency = $("#currency").val();

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






        // Load academies
        $('#branch_id').change(function() {
            const branchId = $(this).val();
            $('#academy_id').empty();
            $('#player_id').empty();

            if (branchId) {
                $.get(`/admin/get-academies-by-branch/${branchId}`, function(data) {
                    $('#academy_id').append('<option value="">Select</option>');
                    data.forEach(academy => {
                        $('#academy_id').append(
                            `<option value="${academy.id}">${academy.name_en}</option>`
                            );
                    });
                });
            }
        });

        // Load programs and players
        $('#academy_id, #category').change(function() {
            const academyId = $('#academy_id').val();
            const category = $('#category').val();
            $('#player_id').empty();
            $('#program_id').empty().append('<option value="">Select</option>');

            if (academyId) {
                $.get(`/admin/get-programs-by-academy/${academyId}`, function(data) {
                    data.forEach(program => {
                        $('#program_id').append(
                            `<option value="${program.id}">${program.name_en}</option>`
                            );
                    });
                });
            }

            if (academyId && (category === 'program' || category === 'uniform')) {
                $('#player-dev').fadeIn();
                $('#program-dev').fadeIn();
                $('#class-dev').fadeIn();
                $('#classes-dev').hide();
                $.get(`/admin/get-players-by-system/${academyId}`, function(data) {
                    const selectedPlayerId = $('#player_id').data('selected');
                    $('#player_id').empty().append('<option value="">Select</option>');
                    data.forEach(player => {
                        const isSelected = player.id == selectedPlayerId ? 'selected' :
                            '';
                        $('#player_id').append(
                            `<option value="${player.id}" ${isSelected}>${player.name}</option>`
                            );
                    });
                });
            } else if (academyId && category === 'class') {
                $('#player-dev').fadeIn();
                $('#program-dev').fadeIn();
                $('#classes-dev').fadeIn();
                $('#class-dev').hide();
                $.get(`/admin/get-players-by-system/${academyId}`, function(data) {
                    const selectedPlayerId = $('#player_id').data('selected');
                    $('#player_id').empty().append('<option value="">Select</option>');
                    data.forEach(player => {
                        const isSelected = player.id == selectedPlayerId ? 'selected' :
                            '';
                        $('#player_id').append(
                            `<option value="${player.id}" ${isSelected}>${player.name}</option>`
                            );
                    });
                });
            } else {
                $('#player-dev').hide();
                $('#program-dev').hide();
                $('#class-dev').hide();
                $('#classes-dev').hide();
            }
        });

        // Load classes when program changes
        $('#program_id').change(function() {
            const programId = $(this).val();
            $('#classes-select').empty();

            if (programId) {
                $.get(`/admin/get-classes-by-program/${programId}`, function(data) {
                    data.forEach(cls => {
                        const label =
                            `${cls.day} | ${cls.start_time}-${cls.end_time} | ${cls.location} | ${cls.coach_name}`;
                        const isSelected = selectedClasses.includes(cls.id) ?
                            'selected' : '';
                        $('#classes-select').append(
                            `<option value="${cls.id}" ${isSelected}>${label}</option>`
                            );
                    });
                    $('#classes-dev').fadeIn();
                });
            } else {
                $('#classes-dev').fadeOut();
            }
        });

        // If editing "class"
        if ($('#category').val() === 'class') {
            $('#player-dev').show();
            $('#program-dev').show();
            $('#classes-dev').show();
            $('#class-dev').hide();

            const academyId = $('#academy_id').val();
            const programId = $('#program_id').val();

            if (academyId) {
                $.get(`/admin/get-players-by-system/${academyId}`, function(data) {
                    const selectedPlayerId = $('#player_id').data('selected');
                    $('#player_id').empty().append('<option value="">Select</option>');
                    data.forEach(player => {
                        const isSelected = player.id == selectedPlayerId ? 'selected' : '';
                        $('#player_id').append(
                            `<option value="${player.id}" ${isSelected}>${player.name}</option>`
                            );
                    });
                });

                $.get(`/admin/get-programs-by-academy/${academyId}`, function(data) {
                    $('#program_id').empty().append('<option value="">Select</option>');
                    data.forEach(program => {
                        const isSelected = program.id == programId ? 'selected' : '';
                        $('#program_id').append(
                            `<option value="${program.id}" ${isSelected}>${program.name_en}</option>`
                            );
                    });

                    if (programId) {
                        $.get(`/admin/get-classes-by-program/${programId}`, function(data) {
                            $('#classes-select').empty();
                            data.forEach(cls => {
                                const label =
                                    `${cls.day} | ${cls.start_time}-${cls.end_time} | ${cls.location} | ${cls.coach_name}`;
                                const isSelected = selectedClasses.includes(cls.id) ?
                                    'selected' : '';
                                $('#classes-select').append(
                                    `<option value="${cls.id}" ${isSelected}>${label}</option>`
                                    );
                            });
                        });
                    }
                });
            }
        }

        // Add item
         $('#add-item').click(function() {
    if (systemItems.length === 0) {
        alert('Please select a system first.');
        return;
    }
    const rowId = Date.now();
    let options = '<option value="">{{ __('payment.filters.select') }}</option>';
    systemItems.forEach(item => {
        options += `<option value="${item.id}" data-price="${item.price}" data-currency="${item.currency ?? 'AED'}">${item.name_en}</option>`;
    });
    const row = `
<tr data-row-id="${rowId}">
    <td><select class="form-control item-select">${options}</select></td>
    <td><input type="number" class="form-control quantity-input" min="1" value="1"></td>
    <td class="item-price-cell">-</td>
    <td><button type="button" class="btn btn-sm remove-item"><i class="la la-trash"></i></button></td>
</tr>`;
    $('#items-table tbody').append(row);
});


        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
        });

        // Auto-calculate totals
        $('input[name="base_price"], input[name="vat_percent"], input[name="paid_amount"]').on('input',
            function() {
                calculateTotals();
            });

        calculateTotals();

        // Serialize items before submit
        $('form').submit(function() {
    // Items
    const items = [];
    $('#items-table tbody tr').each(function() {
        const itemId = $(this).find('.item-select').val();
        const quantity = $(this).find('.quantity-input').val();
        if (itemId && quantity) {
            items.push({
                item_id: itemId,
                quantity: parseInt(quantity),
                price: parseFloat($(this).find('.item-price-cell').text().split(' ')[0]),
                currency: $(this).find('.item-price-cell').text().split(' ')[2] || 'AED'
            });
        }
    });

    $('#items-json').val(JSON.stringify(items));

    // Clear old hidden inputs
    $('input[name="classes[]"]').remove();

    // Add classes as multiple hidden inputs
    const classes = $('#classes-select').val() || [];
    classes.forEach(function(cls) {
        $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'classes[]')
            .val(cls)
            .appendTo('form');
    });
});

    });

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
</script>
