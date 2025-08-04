<div class="form-group col-md-4">
    <label>{{ __('payment.filters.select_system') }}</label>
    <select name="system_id" id="system_id" class="form-control" required>
        <option value="">{{ __('payment.filters.select') }}</option>
        @foreach ($systems as $system)
            <option value="{{ $system->id }}" {{ $payment->system_id == $system->id ? 'selected' : '' }}>{{ $system->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-4">
    <label>{{ __('payment.filters.select_branch') }}</label>
    <select name="branch_id" id="branch_id" class="form-control">
        @if($payment->branch)
            <option value="{{ $payment->branch_id }}" selected>{{ $payment->branch->name }}</option>
        @endif
    </select>
</div>

<div class="form-group col-md-4">
    <label>{{ __('payment.filters.select_academy') }}</label>
    <select name="academy_id" id="academy_id" class="form-control">
        @if($payment->academy)
            <option value="{{ $payment->academy_id }}" selected>{{ $payment->academy->name_en }}</option>
        @endif
    </select>
</div>

<div class="form-group col-md-4">
    <label>{{ __('payment.fields.category') }}</label>
    <select name="category" id="category" class="form-control" required>
        @foreach (\App\Models\Payment::CATEGORIES as $key => $value)
            <option value="{{ $key }}" {{ $payment->category === $key ? 'selected' : '' }}>{{ __('payment.categories.' . $key) }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-4" id="player-dev">
    <label>{{ __('payment.fields.player') }}</label>
    <select name="player_id" id="player_id" class="form-control">
        @if($payment->player)
            <option value="{{ $payment->player_id }}" selected>{{ $payment->player->user->name ?? '' }}</option>
        @endif
    </select>
</div>

<div class="form-group col-md-4" id="program-dev">
    <label>{{ __('payment.fields.program') }}</label>
    <select name="program_id" id="program_id" class="form-control">
        @if($payment->program)
            <option value="{{ $payment->program_id }}" selected>{{ $payment->program->name_en }}</option>
        @endif
    </select>
</div>

<div class="form-group col-md-3" id="class-dev">
    <label>{{ __('payment.fields.class_count') }}</label>
    <input type="number" name="class_count" class="form-control" value="{{ old('class_count', $payment->class_count) }}">
</div>

<div class="form-group col-md-3">
    <label>{{ __('payment.fields.base_price') }}</label>
    <input type="number" name="base_price" class="form-control" step="0.01" value="{{ old('base_price', $payment->base_price) }}" required>
</div>

<div class="form-group col-md-3">
    <label>{{ __('payment.fields.vat_percent') }}</label>
    <input type="number" name="vat_percent" class="form-control" step="0.01" value="{{ old('vat_percent', $payment->vat_percent) }}">
</div>

<div class="form-group col-md-3">
    <label>{{ __('payment.fields.total_price') }}</label>
    <input type="number" name="total_price" class="form-control" step="0.01" value="{{ old('total_price', $payment->total_price) }}" required>
</div>

<div class="form-group col-md-6">
    <label>{{ __('payment.fields.paid_amount') }}</label>
    <input type="number" name="paid_amount" class="form-control" step="0.01" value="{{ old('paid_amount', $payment->paid_amount) }}" required>
</div>

<div class="form-group col-md-6">
    <label>{{ __('payment.fields.remaining_amount') }}</label>
    <input type="number" name="remaining_amount" class="form-control" step="0.01" value="{{ old('remaining_amount', $payment->remaining_amount) }}" readonly>
</div>

<div class="form-group col-md-6">
    <label>{{ __('payment.fields.currency') }}</label>
    <input type="text" name="currency" class="form-control" value="{{ old('currency', $payment->currency) }}" required>
</div>

<div class="form-group col-md-6">
    <label>{{ __('payment.fields.status') }}</label>
    <select name="status" class="form-control" required>
        @foreach (['pending', 'partial', 'paid'] as $status)
            <option value="{{ $status }}" {{ $payment->status === $status ? 'selected' : '' }}>{{ __('payment.status.' . $status) }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-6">
    <label>{{ __('payment.fields.payment_method') }}</label>
    <select name="payment_method_id" class="form-control" required>
        @foreach ($paymentMethods as $method)
            <option value="{{ $method->id }}" {{ $payment->payment_method_id == $method->id ? 'selected' : '' }}>{{ $method->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-12">
    <label>{{ __('payment.fields.note') }}</label>
    <textarea name="note" class="form-control" rows="3">{{ old('note', $payment->note) }}</textarea>
</div>
