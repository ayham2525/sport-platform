@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')
<style>
    .table-nowrap td,
    .table-nowrap th {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-nowrap td {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px; /* adjust as needed */
         font-size: 12px
    }
</style>
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
                    <h3 class="card-label">{{ __('player.titles.players') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('player.titles.players_management') }}</span>
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
                        {{ $academy->name_en }}
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

        <div class="form-group col-md-3">
            <label>{{ __('player.fields.player_code') }}</label>
            <input type="text" name="search" class="form-control" placeholder="{{ __('player.fields.player_code') }}" value="{{ request('search') }}">
        </div>

        <!-- زر الفلترة -->
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
<!-- 1. Assign Program Modal -->
<!-- Assign Program Modal -->

@include('admin.player.partials.assignProgram');



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function () {
        $('.select2').select2();
    });

(function () {
    const $system  = $('#system_id');
    const $branch  = $('#branch_id');
    const $academy = $('#academy_id');

    const placeholderText = @json(__('player.actions.select'));

    // Initialize Select2 if present
    if (typeof $.fn.select2 === 'function') {
        $('.select2').select2({ placeholder: placeholderText, allowClear: true });
    }

    // Named route templates (inside admin group => final name is "admin.getAcademiesByBranch")
    const academiesByBranchUrlTpl = @json(route('admin.getAcademiesByBranch', ['branch_id' => '__ID__']));
    // (Optional) if you also cascade System -> Branch, keep this:
    const branchesBySystemUrlTpl  = @json(route('admin.getBranchesBySystem', ['system_id' => '__ID__']));

    // Helpers
    function resetSelect($el) {
        $el.empty().append(new Option(placeholderText, ''));
        if ($el.hasClass('select2')) $el.trigger('change.select2');
        $el.prop('disabled', false);
    }

    function setLoading($el) {
        $el.empty().append(new Option(placeholderText, ''));
        $el.append(new Option('Loading...', '', true, true));
        if ($el.hasClass('select2')) $el.trigger('change.select2');
        $el.prop('disabled', true);
    }

    function populateSelect($el, items, textKey, selectedValue = '') {
        $el.empty().append(new Option(placeholderText, ''));
        items.forEach(i => $el.append(new Option(i[textKey], i.id)));
        if (selectedValue) $el.val(String(selectedValue));
        if ($el.hasClass('select2')) $el.trigger('change.select2');
        $el.prop('disabled', false);
    }

    // --- MAIN: Load academies for a given branch ---
    function loadAcademiesByBranch(branchId, preselect = '') {
        if (!branchId) { resetSelect($academy); return; }
        setLoading($academy);
        const url = academiesByBranchUrlTpl.replace('__ID__', branchId);
        fetch(url)
            .then(r => r.json())
            .then(list => {
                // Your API returns [{ id, name_en }]
                populateSelect($academy, list, 'name_en', preselect);
            })
            .catch(() => {
                resetSelect($academy);
                console.error('Failed to load academies for branch', branchId);
            });
    }

    // (Optional) If you also want System -> Branch cascade
    function loadBranchesBySystem(systemId, preselect = '') {
        if (!systemId) { resetSelect($branch); resetSelect($academy); return Promise.resolve(); }
        setLoading($branch);
        resetSelect($academy);
        const url = branchesBySystemUrlTpl.replace('__ID__', systemId);
        return fetch(url)
            .then(r => r.json())
            .then(list => {
                // Expecting [{ id, name }]
                populateSelect($branch, list, 'name', preselect);
            })
            .catch(() => {
                resetSelect($branch);
                console.error('Failed to load branches for system', systemId);
            });
    }

    // Events
    $branch.on('change', function () {
        loadAcademiesByBranch(this.value);
    });

    // If you also handle system -> branch
    $system.on('change', function () {
        loadBranchesBySystem(this.value);
    });

    // Rehydrate on initial load (preserve filters on refresh/back)
    const initialBranch  = $branch.val();
    const initialAcademy = @json((string) request('academy_id'));
    if (initialBranch) {
        loadAcademiesByBranch(initialBranch, initialAcademy);
    }

    // Optional: expose your code generator if you use it
    window.generatePlayerCode = function () {
        const prefix = 'PLY-';
        const random = Math.floor(Math.random() * 900000 + 100000);
        const el = document.getElementById('player_code');
        if (el) el.value = `${prefix}${random}`;
    };
})();

$(document).on('click', '.delete-button', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');
    let playerId = form.data('id');

    Swal.fire({
        title: "{{ __('messages.confirm_delete') }}",
        text: "{{ __('messages.delete_warning') }}",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "{{ __('messages.yes_delete') }}",
        cancelButtonText: "{{ __('messages.cancel') }}",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: "{{ __('messages.deleted') }}",
                        text: "{{ __('player.messages.player_deleted_successfully') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // remove row dynamically
                    form.closest('tr').fadeOut(500, function() {
                        $(this).remove();
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: "{{ __('messages.error') }}",
                        text: xhr.responseJSON?.message || "{{ __('messages.something_went_wrong') }}",
                    });
                }
            });
        }
    });
});
</script>

<script>
$(function () {
  // ---------- Route builders ----------
  const programsUrlTpl = @json(route('admin.players.available_programs', ['player' => '__PLAYER__']));
  const classesUrlTpl  = @json(url('admin/programs/__PROGRAM__/classes'));
  const assignUrlTpl   = @json(route('admin.players.assignProgram', ['player' => '__PLAYER__']));

  // ---------- Elements ----------
  const $modal    = $('#assignProgramModal');
  const $form     = $('#assignProgramForm');
  const $errors   = $('#assignProgramErrors');
  const $loader   = $('#assignProgramLoader');
  const $content  = $('#assignProgramContent');

  const $playerId = $('#assign_player_id');
  const $program  = $('#assign_program_id');
  const $classes  = $('#assign_class_ids');

  const $priceBase = $('#price_base');
  const $priceVat  = $('#price_vat');
  const $priceTot  = $('#price_total');

  // ---------- i18n (with safe fallbacks if keys missing) ----------
  const TXT = {
    select: @json(__('player.actions.select')),
    loading: @json(__('player.actions.loading')),
    noPrograms: @json(__('program.messages.no_available_programs')),
    noClasses: @json(__('class.messages.no_classes')),
    selectProgramFirst: @json(__('class.messages.select_program_first')),
    somethingWrong: @json(__('messages.something_went_wrong')),
    done: @json(__('messages.done')),
    error: @json(__('messages.error'))
  };
  Object.keys(TXT).forEach(k => {
    if (typeof TXT[k] === 'string' && TXT[k].includes('.')) {
      // raw key leaked from lang -> use readable fallback
      if (k === 'noPrograms') TXT[k] = 'No available programs';
      if (k === 'noClasses') TXT[k] = 'No classes found';
      if (k === 'selectProgramFirst') TXT[k] = 'Select a program first';
      if (k === 'loading') TXT[k] = 'Loading...';
    }
  });

  // ---------- Helpers ----------
  function showLoader() { $loader.show(); $content.hide(); }
  function showContent(){ $loader.hide(); $content.show(); }
  function showError(msg){ $errors.removeClass('d-none').text(msg); }
  function clearError(){ $errors.addClass('d-none').empty(); }
  function resetProgramSelect() {
    $program.prop('disabled', false).empty()
      .append(new Option(TXT.select, ''));
  }
  function resetClassesSelect() {
    $classes.prop('disabled', true).empty();
  }
  function setPrices(base = 0, vatAmt = 0, total = 0) {
    $priceBase.text(Number(base).toFixed(2));
    $priceVat.text(Number(vatAmt).toFixed(2));
    $priceTot.text(Number(total).toFixed(2));
  }
  // item: { price: "419.00", vat: "5.00", ... }
  function computeTotalsFromItem(item) {
    const base = Number(item.price ?? 0);
    const vatPercent = Number(item.vat ?? 0);
    const vatAmt = +(base * vatPercent / 100);
    return { base, vatAmt, total: base + vatAmt };
  }

  // ---------- Open modal & load programs ----------
  $(document).on('click', '.assign-program-btn', function () {
    const playerId = $(this).data('player-id');
    $playerId.val(playerId);
    clearError();
    setPrices(0,0,0);
    resetProgramSelect();
    resetClassesSelect();

    $classes.prop('disabled', true)
            .append(new Option(TXT.selectProgramFirst, '', true, true));

    showLoader();
    const url = programsUrlTpl.replace('__PLAYER__', playerId);

    $.getJSON(url)
      .done(function(resp){
        // FIX: accept both array and {programs:[...]}
        const list = Array.isArray(resp) ? resp : (resp && Array.isArray(resp.programs) ? resp.programs : []);
        resetProgramSelect();

        if (list.length === 0) {
          $program.append(new Option(TXT.noPrograms, '', true, true));
          $program.prop('disabled', true);
          showContent();
          return;
        }

        list.forEach(p => {
          const text = {{ app()->getLocale() === 'ar' ? '(p.name_ar || p.name_en)' : '(p.name_en || p.name_ar)' }};
          const opt = new Option(text, p.id);
          // stash original program object for price computation later
          $(opt).data('program', p);
          $program.append(opt);
        });

        showContent();
      })
      .fail(function(xhr){
        console.error('available_programs failed:', xhr.status, xhr.responseText);
        showContent();
        showError((xhr.responseJSON && xhr.responseJSON.message) || TXT.somethingWrong);
        $program.prop('disabled', true);
      });

    $modal.modal('show'); // Bootstrap 4
  });

  // ---------- On program change: prices + classes ----------
  $program.on('change', function(){
    clearError();
    setPrices(0,0,0);
    resetClassesSelect();

    const programId = $(this).val();
    if (!programId) return;

    // prices
    const optData = $('option:selected', this).data('program') || {};
    const totals = computeTotalsFromItem(optData);
    setPrices(totals.base, totals.vatAmt, totals.total);

    // classes
    const url = classesUrlTpl.replace('__PROGRAM__', programId);
    $classes.prop('disabled', true).append(new Option(TXT.loading + '...', '', true, true));

    $.getJSON(url)
      .done(function(list){
        $classes.empty();
        if (!Array.isArray(list) || list.length === 0) {
          $classes.prop('disabled', true)
                  .append(new Option(TXT.noClasses, '', true, true));
          return;
        }
        list.forEach(c => {
          const label = `${c.day} | ${c.start_time} - ${c.end_time}${c.location ? ' | ' + c.location : ''}${c.coach_name ? ' | ' + c.coach_name : ''}`;
          $classes.append(new Option(label, c.id));
        });
        $classes.prop('disabled', false);
      })
      .fail(function(xhr){
        console.error('classes fetch failed:', xhr.status, xhr.responseText);
        $classes.prop('disabled', true).empty();
        showError((xhr.responseJSON && xhr.responseJSON.message) || TXT.somethingWrong);
      });
  });

  // ---------- Submit assign ----------
  $form.on('submit', function(e){
    e.preventDefault();
    clearError();

    const playerId = $playerId.val();
    const action   = assignUrlTpl.replace('__PLAYER__', playerId);
    const payload  = $form.serialize();

    const $submit = $form.find('button[type=submit]').prop('disabled', true);

    $.ajax({
      url: action,
      method: 'POST',
      data: payload,
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .done(function(resp){
      if (window.Swal) {
        Swal.fire({ icon: 'success', title: TXT.done, timer: 1200, showConfirmButton: false });
      }
      $modal.modal('hide');
    })
    .fail(function(xhr){
      const msg = (xhr.responseJSON && xhr.responseJSON.message) || TXT.somethingWrong;
      showError(msg);
      if (window.Swal) Swal.fire({ icon:'error', title: TXT.error, text: msg });
      console.error('assign failed:', xhr.status, xhr.responseText);
    })
    .always(function(){
      $submit.prop('disabled', false);
    });
  });
});
</script>





@endsection

