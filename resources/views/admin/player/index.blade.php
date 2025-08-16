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
                                    {{ $system->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.branch') }}</label>
                            <select id="branch_id" name="branch_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>{{ __('player.fields.academy') }}</label>
                            <select id="academy_id" name="academy_id" class="form-control select2">
                                <option value="">{{ __('player.actions.select') }}</option>
                                @foreach ($academies as $academy)
                                <option value="{{ $academy->id }}" {{ request('academy_id') == $academy->id ? 'selected' : '' }}>
                                    {{ $academy->name_en }}</option>
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
                        academySelect.innerHTML += `<option value="${academy.id}">${academy.name}</option>`;
                    });
                });
        });
    });
</script>



@endsection

