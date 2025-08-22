@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .table-nowrap td,
    .table-nowrap th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .table-nowrap td {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px;
        /* adjust as needed */
        font-size: 12px;
    }

</style>
@endpush

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('player.titles.players') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('player.titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('player.titles.players') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">
                        {{ __('player.titles.players') }}
                        <span class="d-block text-muted pt-2 font-size-sm">
                            {{ __('player.titles.players_management') }}
                        </span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\Player::MODEL_NAME))
                    <a href="{{ route('admin.players.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i> {{ __('player.titles.new_record') }}
                    </a>
                    @endif
                    @if (PermissionHelper::hasPermission('export', App\Models\Player::MODEL_NAME))
                    <a href="{{ route('admin.players.export') }}" class="btn btn-success font-weight-bolder ml-2">
                        <i class="la la-file-excel"></i> {{ __('player.actions.export_excel') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="GET" action="{{ route('admin.players.index') }}" class="mb-5">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.system') }}</label>
                            <select id="system_id" name="system_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach ($systems as $system)
                                <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>
                                    {{ $system->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.branch') }}</label>
                            <select id="branch_id" name="branch_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.academy') }}</label>
                            <select id="academy_id" name="academy_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach ($academies as $academy)
                                <option value="{{ $academy->id }}" {{ request('academy_id') == $academy->id ? 'selected' : '' }}>
                                    {{ app()->getLocale()==='ar' ? ($academy->name_ar ?? $academy->name_en) : ($academy->name_en ?? $academy->name_ar) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.sport') }}</label>
                            <select id="sport_id" name="sport_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach ($sports as $sport)
                                <option value="{{ $sport->id }}" {{ request('sport_id') == $sport->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $sport->name_ar : $sport->name_en }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>{{ __('player.fields.id') }}</label>
                            <input type="number" name="player_id" class="form-control" placeholder="{{ __('player.fields.id') }}" value="{{ request('player_id') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.user_name') }}</label>
                            <input type="text" name="user_name" class="form-control" placeholder="{{ __('player.fields.user_name') }}" value="{{ request('user_name') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.player_code') }}</label>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('player.fields.player_code') }}" value="{{ request('search') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="la la-filter"></i> {{ __('player.actions.filter') }}
                            </button>
                        </div>
                        <div class="form-group col-md-2">
                            <a href="{{ route('admin.players.index') }}" class="btn btn-secondary btn-block">
                                <i class="la la-undo"></i> {{ __('player.actions.reset') }}
                            </a>
                        </div>
                    </div>
                </form>


                <div id="players-table-wrapper">
                    @include('admin.player.partials.table', ['players' => $players])
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Assign Program Modal --}}
@include('admin.player.partials.assignProgram')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
/* ========== Select2 (global) ========== */
$(function () {
  if ($.fn && $.fn.select2) {
    $('.select2').select2({
      width: '100%',
      placeholder: @json(__('player.actions.select')),
      allowClear: true,
      dir: @json(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'),
    });
  } else {
    console.warn('Select2 not found on page.');
  }
});
</script>

<script>
/* ========== Cascading filters: System -> Branch -> Academy ========== */
(function () {
  const $system  = $('#system_id');
  const $branch  = $('#branch_id');
  const $academy = $('#academy_id');

  const placeholderText = @json(__('player.actions.select'));
  const nameKeyAcademy  = @json(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en');

  function resetSelect($el) {
    $el.empty().append(new Option(placeholderText, ''));
    if ($.fn && $.fn.select2 && $el.hasClass('select2')) $el.trigger('change.select2');
    $el.prop('disabled', false);
  }

  function setLoading($el) {
    $el.empty().append(new Option(placeholderText, ''));
    $el.append(new Option(@json(__('player.actions.loading')), '', true, true));
    if ($.fn && $.fn.select2 && $el.hasClass('select2')) $el.trigger('change.select2');
    $el.prop('disabled', true);
  }

  function populateSelect($el, items, textKey, selectedValue = '') {
    $el.empty().append(new Option(placeholderText, ''));
    items.forEach(i => {
      const text = i[textKey] || i.name || i.name_en || i.name_ar || ('#' + i.id);
      $el.append(new Option(text, i.id));
    });
    if (selectedValue) $el.val(String(selectedValue));
    if ($.fn && $.fn.select2 && $el.hasClass('select2')) $el.trigger('change.select2');
    $el.prop('disabled', false);
  }

  // Make sure these names exist in routes/web.php (inside admin group)
  const academiesByBranchUrlTpl = @json(route('admin.getAcademiesByBranch', ['branch_id' => '__ID__']));
  const branchesBySystemUrlTpl  = @json(route('admin.getBranchesBySystem', ['system_id' => '__ID__']));

  function loadAcademiesByBranch(branchId, preselect = '') {
    if (!branchId) { resetSelect($academy); return; }
    setLoading($academy);
    const url = academiesByBranchUrlTpl.replace('__ID__', branchId);
    fetch(url)
      .then(r => r.json())
      .then(list => populateSelect($academy, list, nameKeyAcademy, preselect))
      .catch(() => {
        resetSelect($academy);
        console.error('Failed to load academies for branch', branchId);
      });
  }

  function loadBranchesBySystem(systemId, preselect = '') {
    if (!systemId) { resetSelect($branch); resetSelect($academy); return Promise.resolve(); }
    setLoading($branch);
    resetSelect($academy);
    const url = branchesBySystemUrlTpl.replace('__ID__', systemId);
    return fetch(url)
      .then(r => r.json())
      .then(list => populateSelect($branch, list, 'name', preselect))
      .catch(() => {
        resetSelect($branch);
        console.error('Failed to load branches for system', systemId);
      });
  }

  $branch.on('change', function () { loadAcademiesByBranch(this.value); });
  $system.on('change', function () { loadBranchesBySystem(this.value); });

  // Rehydrate on first load
  const initialBranch  = $branch.val();
  const initialAcademy = @json((string) request('academy_id'));
  if (initialBranch) loadAcademiesByBranch(initialBranch, initialAcademy);
})();
</script>

<script>
/* ========== Assign Program modal ========== */
$(function () {
  // Route builders
  const programsUrlTpl = @json(route('admin.players.available_programs', ['player' => '__PLAYER__']));
  const classesUrlTpl  = @json(url('admin/programs/__PROGRAM__/classes'));
  const assignUrlTpl   = @json(route('admin.players.assignProgram', ['player' => '__PLAYER__']));

  // Elements
  const $modal   = $('#assignProgramModal');
  const $form    = $('#assignProgramForm');
  const $errors  = $('#assignProgramErrors');
  const $loader  = $('#assignProgramLoader');
  const $content = $('#assignProgramContent');

  const $playerId = $('#assign_player_id');
  const $program  = $('#assign_program_id');
  const $classes  = $('#assign_class_ids');

  const $priceBase = $('#price_base');
  const $priceVat  = $('#price_vat');
  const $priceTot  = $('#price_total');

  const TXT = {
    select: @json(__('player.actions.select')),
    loading: @json(__('player.actions.loading')),
    noPrograms: @json(__('program.messages.no_available_programs')),
    noClasses: @json(__('class.messages.no_classes')),
    selectProgramFirst: @json(__('class.messages.select_program_first')),
    somethingWrong: @json(__('messages.something_went_wrong')),
    done: @json(__('messages.done')),
    error: @json(__('messages.error')),
  };
  // Fallback if lang keys are missing on server
  Object.keys(TXT).forEach(k => {
    if (typeof TXT[k] === 'string' && TXT[k].includes('.')) {
      if (k === 'noPrograms')         TXT[k] = 'No available programs';
      if (k === 'noClasses')          TXT[k] = 'No classes found';
      if (k === 'selectProgramFirst') TXT[k] = 'Select a program first';
      if (k === 'loading')            TXT[k] = 'Loading...';
    }
  });

  function showLoader()  { $loader.show();  $content.hide(); }
  function showContent() { $loader.hide();  $content.show(); }
  function showError(msg){ $errors.removeClass('d-none').text(msg); }
  function clearError()  { $errors.addClass('d-none').empty(); }

  function resetProgramSelect() {
    $program.prop('disabled', false).empty().append(new Option(TXT.select, ''));
    if ($.fn && $.fn.select2) $program.trigger('change.select2');
  }
  function resetClassesSelect() {
    $classes.prop('disabled', true).empty();
    if ($.fn && $.fn.select2) $classes.trigger('change.select2');
  }

  function setPrices(base=0, vatAmt=0, total=0) {
    $priceBase.text(Number(base).toFixed(2));
    $priceVat.text(Number(vatAmt).toFixed(2));
    $priceTot.text(Number(total).toFixed(2));
  }

  // item: { price: "419.00", vat: "5.00", ... }
  function computeTotalsFromItem(item) {
    const base       = Number(item.price ?? 0);
    const vatPercent = Number(item.vat ?? 0);
    const vatAmt     = +(base * vatPercent / 100);
    return { base, vatAmt, total: base + vatAmt };
  }

  // Open modal
  $(document).on('click', '.assign-program-btn', function () {
    const playerId = $(this).data('player-id');
    $playerId.val(playerId);
    clearError();
    setPrices(0,0,0);
    resetProgramSelect();
    resetClassesSelect();

    // Select2 inside modal (with proper dropdownParent)
    if ($.fn && $.fn.select2) {
      $('#assignProgramModal .select2').select2({
        width: '100%',
        dropdownParent: $modal,
        placeholder: TXT.select,
        allowClear: true,
        dir: @json(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'),
      });
    }

    $classes.prop('disabled', true)
      .append(new Option(TXT.selectProgramFirst, '', true, true));

    showLoader();
    const url = programsUrlTpl.replace('__PLAYER__', playerId);

    $.getJSON(url)
      .done(function (resp) {
        try {
          const list = Array.isArray(resp) ? resp : (resp && Array.isArray(resp.programs) ? resp.programs : []);
          resetProgramSelect();

          if (!list.length) {
            $program.append(new Option(TXT.noPrograms, '', true, true)).prop('disabled', true);
            return;
          }

          list.forEach(p => {
            const text = @json(app()->getLocale() === 'ar') ? (p.name_ar || p.name_en) : (p.name_en || p.name_ar);
            const opt  = new Option(text, p.id);
            $(opt).data('program', p);
            $program.append(opt);
          });

          if ($.fn && $.fn.select2) $program.trigger('change.select2');
        } finally {
          showContent(); // always hide loader
        }
      })
      .fail(function (xhr) {
        showContent();
        console.error('available_programs failed:', xhr.status, xhr.responseText);
        showError((xhr.responseJSON && xhr.responseJSON.message) || TXT.somethingWrong);
        $program.prop('disabled', true);
      });

    $modal.modal('show');
  });

  // On program change: compute price + load classes
  $program.on('change', function () {
    clearError();
    setPrices(0,0,0);
    resetClassesSelect();

    const programId = $(this).val();
    if (!programId) return;

    const optData = $('option:selected', this).data('program') || {};
    const totals  = computeTotalsFromItem(optData);
    setPrices(totals.base, totals.vatAmt, totals.total);

    const url = classesUrlTpl.replace('__PROGRAM__', programId);
    $classes.prop('disabled', true).append(new Option(TXT.loading + '...', '', true, true));

    $.getJSON(url)
      .done(function (list) {
        $classes.empty();
        if (!Array.isArray(list) || !list.length) {
          $classes.prop('disabled', true).append(new Option(TXT.noClasses, '', true, true));
          return;
        }
        list.forEach(c => {
          const label = `${c.day} | ${c.start_time} - ${c.end_time}${c.location ? ' | ' + c.location : ''}${c.coach_name ? ' | ' + c.coach_name : ''}`;
          $classes.append(new Option(label, c.id));
        });
        $classes.prop('disabled', false);
        if ($.fn && $.fn.select2) $classes.trigger('change.select2');
      })
      .fail(function (xhr) {
        console.error('classes fetch failed:', xhr.status, xhr.responseText);
        $classes.prop('disabled', true).empty();
        showError((xhr.responseJSON && xhr.responseJSON.message) || TXT.somethingWrong);
      });
  });

  // Submit assign
  $form.on('submit', function (e) {
    e.preventDefault();
    clearError();

    const playerId = $playerId.val();
    const action   = assignUrlTpl.replace('__PLAYER__', playerId);
    const payload  = $form.serialize();
    const $submit  = $form.find('button[type=submit]').prop('disabled', true);

    $.ajax({
      url: action,
      method: 'POST',
      data: payload,
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
    })
    .done(function () {
      if (window.Swal) {
        Swal.fire({ icon: 'success', title: TXT.done, timer: 1200, showConfirmButton: false });
      }
      $modal.modal('hide');
    })
    .fail(function (xhr) {
      const msg = (xhr.responseJSON && xhr.responseJSON.message) || TXT.somethingWrong;
      showError(msg);
      if (window.Swal) Swal.fire({ icon: 'error', title: TXT.error, text: msg });
      console.error('assign failed:', xhr.status, xhr.responseText);
    })
    .always(function () {
      $submit.prop('disabled', false);
    });
  });

  // Delete player
  $(document).on('click', '.delete-button', function (e) {
    e.preventDefault();
    const form = $(this).closest('form');

    Swal.fire({
      title: @json(__('messages.confirm_delete')),
      text: @json(__('messages.delete_warning')),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: @json(__('messages.yes_delete')),
      cancelButtonText: @json(__('messages.cancel')),
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(form.attr('action'), form.serialize())
          .done(function () {
            Swal.fire({
              icon: 'success',
              title: @json(__('messages.deleted')),
              text: @json(__('player.messages.player_deleted_successfully')),
              timer: 2000,
              showConfirmButton: false
            });
            form.closest('tr').fadeOut(500, function(){ $(this).remove(); });
          })
          .fail(function (xhr) {
            Swal.fire({
              icon: 'error',
              title: @json(__('messages.error')),
              text: (xhr.responseJSON && xhr.responseJSON.message) || @json(__('messages.something_went_wrong'))
            });
          });
      }
    });
  });
});
</script>
@endpush

