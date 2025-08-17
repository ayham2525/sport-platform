@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">
    <i class="la la-users mr-2"></i> {{ $academy->name_en }} - {{ __('academy.players') }}
</h5>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">

            {{-- Header --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ __('player.titles.players_list') }}</h3>
                <a href="{{ route('admin.academies.players.export', ['id' => $academy->id, 'status' => request('status')]) }}" class="btn btn-success btn-sm">
                    <i class="la la-file-excel"></i> {{ __('player.export_excel') }}
                </a>
            </div>

            <div class="card-body">
                {{-- Filter --}}
                <form method="GET" class="mb-4">
                    <div class="form-inline">
                        <label class="mr-2">{{ __('player.fields.status') }}</label>
                        <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                            <option value="">{{ __('player.all') }}</option>
                            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>
                                {{ __('player.status.active') }} ({{ $activeCount }})
                            </option>
                            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>
                                {{ __('player.status.expired') }} ({{ $inactiveCount }})
                            </option>
                        </select>
                    </div>
                </form>

                {{-- Table --}}
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('player.name') }}</th>
                            <th>{{ __('player.email') }}</th>
                            <th>{{ __('player.phone') }}</th>
                            <th>{{ __('player.fields.status') }}</th>
                            <th>{{ __('player.last_payment.start_date') }}</th>
                            <th>{{ __('player.last_payment.end_date') }}</th>
                            <th>{{ __('player.created_at') }}</th>
                            <th class="text-center">{{ __('player.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($players as $index => $player)
                        <tr>
                            <td>{{ $players->firstItem() + $index }}</td>
                            <td>{{ $player->user->name ?? '-' }}</td>
                            <td>{{ $player->user->email ?? '-' }}</td>
                            <td>{{ $player->guardian_phone ?? '-' }}</td>
                            <td>
                                @if ($player->status === 'active')
                                <span class="badge badge-success">{{ __('player.status.active') }}</span>
                                @else
                                <span class="badge badge-danger">{{ __('player.status.expired') }}</span>
                                @endif
                            </td>
                            <td>
                                {{ optional($player->payments->first()?->start_date)->format('Y-m-d') ?? '-' }}
                            </td>
                            <td>
                                {{ optional($player->payments->first()?->end_date)->format('Y-m-d') ?? '-' }}
                            </td>
                            <td>{{ $player->created_at?->format('Y-m-d') ?? '-' }}</td>
                            <td class="text-center">
                                {{-- View --}}
                                <a href="{{ route('admin.players.show', $player->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('messages.view') }}">
                                    <i class="la la-eye"></i>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.players.edit', $player->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('messages.edit') }}">
                                    <i class="la la-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                {{ __('player.no_players_found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $players->appends(request()->all())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

