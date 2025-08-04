<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form id="paymentForm" action="{{ route('admin.player_payments.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <input type="hidden" name="player_id" value="{{ $player->id }}">
      <input type="hidden" name="branch_id" value="{{ $player->branch_id }}">

      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">
          <i class="la la-plus-circle"></i> {{ __('player.actions.add_payment') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('player.actions.cancel') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Readonly Info -->
        <div class="form-row mb-3">
          <div class="col">
            <label><i class="la la-bank"></i> {{ __('player.fields.branch') }}</label>
            <input type="text" class="form-control" value="{{ $player->branch->name ?? '-' }}" readonly>
          </div>
          <div class="col">
            <label><i class="la la-building"></i> {{ __('player.fields.academy') }}</label>
            <input type="text" class="form-control" value="{{ $player->academy->name_en ?? '-' }}" readonly>
          </div>
          <div class="col">
            <label><i class="la la-user"></i> {{ __('player.fields.full_name') }}</label>
            <input type="text" class="form-control" value="{{ $player->user->name }}" readonly>
          </div>
        </div>

        <!-- Dates & Status -->
        <div class="form-row mb-3">
          <div class="col">
            <label><i class="la la-calendar"></i> {{ __('player.fields.payment_date') }}</label>
            <input type="date" name="payment_date" class="form-control" required>
          </div>
          <div class="col">
            <label><i class="la la-hourglass-start"></i> {{ __('player.fields.start_date') }}</label>
            <input type="date" name="start_date" class="form-control">
          </div>
          <div class="col">
            <label><i class="la la-hourglass-end"></i> {{ __('player.fields.end_date') }}</label>
            <input type="date" name="end_date" class="form-control">
          </div>
        </div>

        <div class="form-row mb-3">
          <div class="col">
            <label><i class="la la-refresh"></i> {{ __('player.fields.status_student') }}</label>
            <select name="status_student" class="form-control">
              <option value="renewal">{{ __('Renewal') }}</option>
              <option value="new">{{ __('New') }}</option>
            </select>
          </div>
          <div class="col">
            <label><i class="la la-credit-card"></i> {{ __('player.fields.system') }}</label>
            <select name="payment_method_id" class="form-control">
              @foreach(\App\Models\PaymentMethod::all() as $method)
                <option value="{{ $method->id }}">{{ app()->getLocale() == 'ar' ? $method->name_ar : $method->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col">
            <label><i class="la la-check-circle"></i> {{ __('player.fields.status') }}</label>
            <select name="status" class="form-control" required>
              <option value="pending">{{ __('Pending') }}</option>
              <option value="partial">{{ __('Partial') }}</option>
              <option value="paid">{{ __('Paid') }}</option>
            </select>
          </div>
        </div>

        <!-- Financial -->
        <div class="form-row mb-3">
          <div class="col">
            <label><i class="la la-dollar"></i> {{ __('player.fields.total_price') }}</label>
            <input type="number" step="0.01" name="total_price" class="form-control" required>
          </div>
          <div class="col">
            <label><i class="la la-tag"></i> {{ __('player.fields.discount') }}</label>
            <input type="number" step="0.01" name="discount" class="form-control">
          </div>
          <div class="col">
            <label><i class="la la-barcode"></i> {{ __('player.fields.reset_number') }}</label>
            <input type="text" name="reset_number" class="form-control">
          </div>
        </div>

        <!-- Class Time -->
        <div class="form-row mb-3">
          <div class="col">
            <label><i class="la la-clock-o"></i> {{ __('player.fields.class_time_from') }}</label>
            <input type="time" name="class_time_from" class="form-control">
          </div>
          <div class="col">
            <label><i class="la la-clock-o"></i> {{ __('player.fields.class_time_to') }}</label>
            <input type="time" name="class_time_to" class="form-control">
          </div>
        </div>

        <!-- Receipt File & Note -->
        <div class="form-row mb-3">
          <div class="col">
            <label><i class="la la-file"></i> {{ __('player.fields.receipt_file') }}</label>
            <input type="file" name="receipt_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <div id="existing-receipt" class="mt-2" style="display:none;">
              <a href="#" target="_blank" class="btn btn-sm btn-light-primary">
                <i class="la la-download"></i> {{ __('View Current Receipt') }}
              </a>
            </div>
          </div>
          <div class="col">
            <label><i class="la la-sticky-note"></i> {{ __('player.fields.note') }}</label>
            <textarea name="note" class="form-control" rows="2"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
          <i class="la la-save"></i> {{ __('player.actions.save') }}
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="la la-times"></i> {{ __('player.actions.cancel') }}
        </button>
      </div>
    </form>
  </div>
</div>
