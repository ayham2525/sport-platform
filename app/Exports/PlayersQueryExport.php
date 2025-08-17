<?php

namespace App\Exports;

use App\Models\Player;
use App\Models\Branch;
use App\Models\Academy;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlayersQueryExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function query()
    {
        $user = Auth::user();

        $query = Player::query()->with('user');

        // 🔹 Role restrictions
        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id) {
                    $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                    $academyIds = Academy::whereIn('branch_id', $branchIds)->pluck('id')->toArray();
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'branch_admin':
                if ($user->branch_id) {
                    $academyIds = Academy::where('branch_id', $user->branch_id)->pluck('id')->toArray();
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'academy_admin':
            case 'coach':
            case 'player':
                $academyIds = is_array($user->academy_id)
                    ? $user->academy_id
                    : json_decode($user->academy_id, true) ?? [];

                if (!empty($academyIds)) {
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            default:
                // full_admin or unknown role: no restrictions
                break;
        }

        // 🔹 Status filter
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            __('player.name'),
            __('player.email'),
            __('player.phone'),
            __('player.fields.status'),
             __('player.last_payment.start_date'),
        __('player.last_payment.end_date'),
            __('player.created_at'),
        ];
    }

    public function map($player): array
    {
        $lastPayment = $player->payments()->latest('payment_date')->first();

        return [
            $player->user->name ?? '-',
            $player->user->email ?? '-',
            $player->guardian_phone ?? '-',
            $player->status === 'active'
                ? __('player.status.active')
                : __('player.status.expired'),
                  optional($lastPayment?->start_date)->format('Y-m-d') ?? '-',
    optional($lastPayment?->end_date)->format('Y-m-d') ?? '-',
            optional($player->created_at)->format('Y-m-d') ?? '-',
        ];
    }
}
