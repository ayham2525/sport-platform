<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Branch;
use App\Models\Academy;
use App\Models\System;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $user = Auth::user();

        $query = Payment::with(['player.user', 'program', 'branch', 'academy', 'paymentMethod']);

        // Apply role-based filters
        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id) {
                    $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                    $academyIds = Academy::whereIn('branch_id', $branchIds)->pluck('id');
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'branch_admin':
                if ($user->branch_id) {
                    $academyIds = Academy::where('branch_id', $user->branch_id)->pluck('id');
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
        }

        // Request-based filtering
        if ($this->request->filled('system_id')) {
            $query->whereHas('branch.system', function ($q) {
                $q->where('id', $this->request->system_id);
            });
        }

        if ($this->request->filled('branch_id')) {
            $query->where('branch_id', $this->request->branch_id);
        }

        if ($this->request->filled('academy_id')) {
            $query->where('academy_id', $this->request->academy_id);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->whereHas('player.user', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        $payments = $query->get();

        return view('admin.exports.payments', [
            'payments' => $payments
        ]);
    }
}
