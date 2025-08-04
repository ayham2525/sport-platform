@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        <i class="fas fa-user-edit text-warning mr-1"></i> {{ __('player.titles.edit_record') }}
    </h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="fas fa-home mr-1"></i> {{ __('player.titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.players.index') }}" class="text-muted">
            <i class="fas fa-users mr-1"></i> {{ __('player.titles.players') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('player.titles.edit_record') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-edit text-warning mr-2"></i> {{ __('player.titles.edit_record') }}</h3>
        </div>
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

            <form method="POST" action="{{ route('admin.players.update', $player->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        @include('admin.player.partials.form', [
                            'mode' => 'edit',
                            'player' => $player,
                            'systems' => $systems,
                            'branches' => $branches,
                            'academies' => $academies,
                            'nationalities' => $nationalities,
                            'sports' => $sports
                        ])
                    </div>
                </div>


                <div class="form-group text-right mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> {{ __('player.actions.update') }}
                    </button>
                    <a href="{{ route('admin.players.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('player.actions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function generatePlayerCode() {
        const prefix = 'PLY-';
        const random = Math.floor(Math.random() * 900000 + 100000);
        const code = `${prefix}${random}`;
        document.getElementById('player_code').value = code;
    }

    window.addEventListener('DOMContentLoaded', function () {
        $('.select2').select2({
            placeholder: "{{ __('player.actions.select') }}",
            allowClear: true
        });

        const systemSelect = document.getElementById('system_id');
        const branchSelect = document.getElementById('branch_id');
        const academySelect = document.getElementById('academy_id');

        const selectText = {!! json_encode(__('player.actions.select')) !!};
        const selectOption = `<option value="">${selectText}</option>`;

        const getBranchesBySystemRouteTemplate = "{{ route('admin.getBranchesBySystem', ['system_id' => '__ID__']) }}";
        const getAcademiesByBranchRouteTemplate = "{{ route('admin.getAcademiesByBranch', ['branch_id' => '__ID__']) }}";

        systemSelect.addEventListener('change', function () {
            const systemId = this.value;
            if (!systemId) return;

            const url = getBranchesBySystemRouteTemplate.replace('__ID__', systemId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    branchSelect.innerHTML = selectOption;
                    data.forEach(branch => {
                        branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
                    });
                    branchSelect.dispatchEvent(new Event('change'));
                });
        });

        branchSelect.addEventListener('change', function () {
            const branchId = this.value;
            if (!branchId) return;

            const url = getAcademiesByBranchRouteTemplate.replace('__ID__', branchId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    academySelect.innerHTML = selectOption;
                    data.forEach(academy => {
                        academySelect.innerHTML += `<option value="${academy.id}">${academy.name_en}</option>`;
                    });
                });
        });
    });
</script>


@endsection
