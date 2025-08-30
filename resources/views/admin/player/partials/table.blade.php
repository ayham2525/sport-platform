@php use App\Helpers\PermissionHelper; @endphp

<div class="table-responsive table-nowrap" style="overflow-y: auto;">
    <table class="table table-bordered table-hover table-nowrap">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>{{ __('player.fields.id') }}</th>
                <th>{{ __('player.fields.name') }}</th>
                <th>{{ __('player.fields.code') }}</th>
                <th>{{ __('player.fields.sport') }}</th>
                <th>{{ __('player.fields.branch') }}</th>
                <th>{{ __('player.fields.academy') }}</th>
                <th>{{ __('player.fields.nationality') }}</th>
                <th>{{ __('player.fields.guardian_phone') }}</th>

                <th>{{ __('player.fields.gender') }}</th>
                 <th>{{ __('player.fields.birth_date') }}</th>
                <th>{{ __('player.fields.created_at') }}</th>
                <th>{{ __('player.fields.payment_start_date') }}</th>
                <th>{{ __('player.fields.payment_end_date') }}</th>
                <th>{{ __('player.fields.payment_status') }}</th>
                <th>{{ __('player.fields.status') }}</th>
                <th>{{ __('player.fields.card_serial_number') }}</th>
                <th>{{ __('player.fields.program') }}</th>
                <th>{{ __('player.fields.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($players as $index => $player)
            @php
            // latest program by pivot timestamps
            $latestProgram = $player->programs
            ->sortByDesc(function($p){
            return $p->pivot->updated_at ?? $p->pivot->created_at;
            })
            ->first();

            $latestPayment = $player->payments()->latest('end_date')->first();

            $now = \Carbon\Carbon::now();
            if ($latestPayment && $latestPayment->end_date) {
            if ($now->lt($latestPayment->end_date)) {
            $statusIcon = '<i class="la la-check-circle text-success"></i>';
            $statusText = __('payment.status.active');
            } else {
            $statusIcon = '<i class="la la-times-circle text-danger"></i>';
            $statusText = __('payment.status.expired');
            }
            } else {
            $statusIcon = '<i class="la la-minus-circle text-muted"></i>';
            $statusText = __('payment.status.unknown');
            }
            @endphp

            <tr>
                <td>{{ $index + $players->firstItem() }}</td>
                <td>{{ $player->id ?? '-' }}</td>
                <td>{{ $player->user->name ?? '-' }}</td>
                <td>{{ $player->player_code ?? '-' }}</td>
                <td>{{ app()->getLocale() === 'ar' ? ($player->sport->name_ar ?? '-') : ($player->sport->name_en ?? '-') }}</td>
                <td>{{ $player->branch->name ?? '-' }}</td>
                <td>{{ app()->getLocale()==='ar' ? ($player->academy->name_ar ?? $player->academy->name_en ?? '-') : ($player->academy->name_en ?? $player->academy->name_ar ?? '-') }}</td>
                <td>{{ app()->getLocale() === 'ar' ? ($player->nationality->name_ar ?? '-') : ($player->nationality->name_en ?? '-') }}</td>
                <td>{{ $player->guardian_phone ?? '-' }}</td>
                <td>{{ $player->gender ? __('player.fields.' . $player->gender) : '-' }}</td>
                <td>{{ $player->birth_date ? \Carbon\Carbon::parse($player->birth_date)->format('Y-m-d') : '-' }}</td>
                <td>{{ optional($player->user->created_at)->format('Y-m-d') }}</td>
                <td>{{ $latestPayment && $latestPayment->start_date ? $latestPayment->start_date->format('d/m/Y') : '-' }}</td>
                <td>{{ $latestPayment && $latestPayment->end_date   ? $latestPayment->end_date->format('d/m/Y')   : '-' }}</td>
                <td>{!! $statusIcon !!} <span class="ml-1">{{ $statusText }}</span></td>
               <td class="align-middle">
    @php
        $status = $player->status ?? 'active';
        $badge  = [
            'active'  => 'success',
            'expired' => 'danger',
            'stopped' => 'secondary',
        ][$status] ?? 'light';

        $icon = [
            'active'  => 'la la-check-circle',
            'expired' => 'la la-times-circle',
            'stopped' => 'la la-pause-circle',
        ][$status] ?? 'la la-minus-circle';
    @endphp

    <div class="mt-1">
        <span class="badge badge-{{ $badge }}">
            <i class="{{ $icon }} text-white"></i>
            {{ __('player.status.' . $status) }}
        </span>
    </div>
</td>


                <td>{{ $player->user->card_serial_number }}</td>
                <td>
                    @if($latestProgram)
                    {{ app()->getLocale()==='ar'
                            ? ($latestProgram->name_ar ?? $latestProgram->name_en)
                            : ($latestProgram->name_en ?? $latestProgram->name_ar) }}
                    @else
                    -
                    @endif
                </td>
                <td nowrap>
                    @if (PermissionHelper::hasPermission('view', App\Models\Player::MODEL_NAME))
                    <a href="{{ route('admin.players.show', $player->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('player.actions.view') }}">
                        <i class="la la-eye"></i>
                    </a>
                    @endif

                    <button class="btn btn-sm btn-clean btn-icon assign-program-btn" data-player-id="{{ $player->id }}" data-toggle="modal" data-target="#assignProgramModal" title="{{ __('player.actions.assign_program') }}">
                        <i class="la la-link"></i>
                    </button>

                    <a href="{{ route('admin.cards.scan', ['player_id' => $player->id]) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('player.actions.scan_card') }}">
                        <i class="la la-id-card"></i>
                    </a>

                    @if (PermissionHelper::hasPermission('update', App\Models\Player::MODEL_NAME))
                    <a href="{{ route('admin.players.edit', $player->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('player.actions.edit') }}">
                        <i class="la la-edit"></i>
                    </a>
                    @endif

                    @if (PermissionHelper::hasPermission('delete', App\Models\Player::MODEL_NAME))
                    <form action="{{ route('admin.players.destroy', $player->id) }}" method="POST" class="d-inline-block delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('player.actions.delete') }}">
                            <i class="la la-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $players->withQueryString()->links('pagination::bootstrap-4') }}
</div>

