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
        max-width: 200px; /* adjust as needed */
        font-size: 12px;
    }
</style>

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        {{ __('reports.expired_players') }}
    </h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('messages.dashboard') }}</a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('reports.expired_players') }}</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                <i class="la la-exclamation-triangle text-danger"></i>
                {{ __('reports.expired_players') }}
            </h3>
        </div>
        <div class="card-body">

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.reports.expired_players.index') }}" class="form-inline mb-4">
                <div class="form-group mr-3">
                    <label for="start_date" class="mr-2">{{ __('reports.start_date') }}</label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ $start_date }}" class="form-control">
                </div>
                <div class="form-group mr-3">
                    <label for="end_date" class="mr-2">{{ __('reports.end_date') }}</label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ $end_date }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="la la-filter"></i> {{ __('reports.filter') }}
                </button>
            </form>

            {{-- Expired Players Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('player.fields.name') }}</th>
                            <th>{{ __('player.fields.email') }}</th>
                            <th>{{ __('player.fields.branch') }}</th>
                            <th>{{ __('player.fields.academy') }}</th>
                            <th>{{ __('player.fields.nationality') }}</th>
                            <th>{{ __('player.fields.sport') }}</th>
                            <th>{{ __('player.fields.guardian_name') }}</th>
                            <th>{{ __('player.fields.guardian_phone') }}</th>
                            <th>{{ __('player.fields.birth_date') }}</th>
                            <th>{{ __('player.fields.gender') }}</th>
                            <th>{{ __('reports.last_expired_payment') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expiredPlayers as $i => $player)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.players.show', $player->id) }}">
                                        {{ $player->user->name }}
                                    </a>
                                </td>
                                <td>{{ $player->user->email }}</td>
                                <td>{{ optional($player->branch)->name }}</td>
                                <td>{{ optional($player->academy)->name }}</td>
                                <td>{{ optional($player->nationality)->name }}</td>
                                <td>{{ optional($player->sport)->name }}</td>
                                <td>{{ $player->guardian_name }}</td>
                                <td>{{ $player->guardian_phone }}</td>
                                <td>{{ $player->birth_date }}</td>
                                <td>{{ ucfirst($player->gender) }}</td>
                                <td>
                                    {{ optional($player->payments()->latest('end_date')->first())->end_date?->format('Y-m-d') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">
                                    {{ __('reports.no_expired_players') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $expiredPlayers->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>
@endsection
