<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\State;
use App\Models\Branch;
use App\Models\Player;
use App\Models\System;
use App\Models\Academy;
use App\Models\Country;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\DB;


class BranchController extends Controller
{
    /** Display a listing of the branches.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
  public function index(Request $request)
{
    if (!PermissionHelper::hasPermission('view', Branch::MODEL_NAME)) {
        return PermissionHelper::denyAccessResponse();
    }

    $user = auth()->user();

    // Base query
    $query = Branch::with('city.state.country', 'system');

    // Determine allowed branches by role
    $allowedBranchIds = null; // null = no restriction (full_admin)

    switch ($user->role) {
        case 'system_admin':
            if (!empty($user->system_id)) {
                $allowedBranchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                $query->whereIn('id', $allowedBranchIds);
            } else {
                $query->whereRaw('0 = 1');
                $allowedBranchIds = collect(); // empty scope
            }
            break;

        case 'branch_admin':
            if (!empty($user->branch_id)) {
                $allowedBranchIds = collect([(int)$user->branch_id]);
                $query->where('id', (int)$user->branch_id);
            } else {
                $query->whereRaw('0 = 1');
                $allowedBranchIds = collect();
            }
            break;

        case 'academy_admin':
        case 'coach':
        case 'player':
            // user->academy_id may be JSON array or single id
            $academyIds = json_decode($user->academy_id, true);
            if (is_null($academyIds)) {
                // handle non-JSON (single id)
                $academyIds = !empty($user->academy_id) ? [(int)$user->academy_id] : [];
            }
            $academyIds = array_filter((array)$academyIds);

            if (!empty($academyIds)) {
                $allowedBranchIds = Academy::whereIn('id', $academyIds)->pluck('branch_id')->unique()->values();
                if ($allowedBranchIds->isNotEmpty()) {
                    $query->whereIn('id', $allowedBranchIds);
                } else {
                    $query->whereRaw('0 = 1');
                    $allowedBranchIds = collect();
                }
            } else {
                $query->whereRaw('0 = 1');
                $allowedBranchIds = collect();
            }
            break;

        default: // full_admin (or any other super role)
            // no restriction
            break;
    }

    // Apply incoming filters (still within allowed scope)
    if ($request->filled('country_id')) {
        $query->whereHas('city.state', function ($q) use ($request) {
            $q->where('country_id', $request->country_id);
        });
    }

    if ($request->filled('state_id')) {
        $query->whereHas('city', function ($q) use ($request) {
            $q->where('state_id', $request->state_id);
        });
    }

    if ($request->filled('city_id')) {
        $query->where('city_id', $request->city_id);
    }

    if ($request->filled('system_id')) {
        $query->where('system_id', $request->system_id);
    }

    if ($request->filled('status')) {
        $query->where('is_active', $request->status);
    }

    // Result list (paginate for consistency with other indexes)
    $perPage  = (int)($request->get('per_page', 10));
    $branches = $query->latest()->paginate($perPage)->appends($request->query());

    // Build scoped filter options
    if (is_null($allowedBranchIds)) {
        // Full scope
        $countries = Country::where('is_active', 1)->get();
        $states    = $request->filled('country_id')
            ? State::where('country_id', $request->country_id)->where('is_active', 1)->get()
            : collect();
        $cities    = $request->filled('state_id')
            ? City::where('state_id', $request->state_id)->where('is_active', 1)->get()
            : collect();
        $systems   = System::all();
    } else {
        // Restricted scope
        if ($allowedBranchIds instanceof \Illuminate\Support\Collection) {
            $allowedBranchIds = $allowedBranchIds->all();
        }

        if (empty($allowedBranchIds)) {
            $countries = collect();
            $states    = collect();
            $cities    = collect();
            $systems   = collect();
        } else {
            $scopedBranches = Branch::whereIn('id', $allowedBranchIds)->get(['id', 'city_id', 'system_id']);

            $cityIds   = $scopedBranches->pluck('city_id')->filter()->unique()->values();
            $stateIds  = City::whereIn('id', $cityIds)->pluck('state_id')->filter()->unique()->values();
            $countryIds= State::whereIn('id', $stateIds)->pluck('country_id')->filter()->unique()->values();
            $systemIds = $scopedBranches->pluck('system_id')->filter()->unique()->values();

            $countries = Country::whereIn('id', $countryIds)->where('is_active', 1)->get();

            if ($request->filled('country_id')) {
                $states = State::where('country_id', $request->country_id)
                    ->whereIn('id', $stateIds)
                    ->where('is_active', 1)
                    ->get();
            } else {
                $states = State::whereIn('id', $stateIds)->where('is_active', 1)->get();
            }

            if ($request->filled('state_id')) {
                $cities = City::where('state_id', $request->state_id)
                    ->whereIn('id', $cityIds)
                    ->where('is_active', 1)
                    ->get();
            } else {
                $cities = City::whereIn('id', $cityIds)->where('is_active', 1)->get();
            }

            $systems = System::whereIn('id', $systemIds)->get();
        }
    }

    return view('admin.branch.index', compact('branches', 'countries', 'states', 'cities', 'systems'));
}


    /**
     * Show the form for creating a new branch.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!PermissionHelper::hasPermission('create', Branch::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $countries = Country::where('is_active', 1)->get();
        $states = collect();
        $cities = collect();
        $systems = System::all();

        return view('admin.branch.create', compact('countries', 'states', 'cities', 'systems'));
    }

    /**
     * Store a newly created branch in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', Branch::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'system_id' => 'required|exists:systems,id',
            'is_active' => 'nullable|boolean',
            'maximum_player_number' => 'nullable|integer|min:1',
        ]);

        Branch::create([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'name_ur' => $request->name_ur,
            'city_id' => $request->city_id,
            'system_id' => $request->system_id,
            'address' => $request->address,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? false,
            'maximum_player_number' => $request->maximum_player_number ?? null,
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified branch.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        if (!PermissionHelper::hasPermission('view', Branch::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $branch = Branch::with('city.state.country', 'system')->findOrFail($id);
        return view('admin.branch.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified branch.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if (!PermissionHelper::hasPermission('update', Branch::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $branch = Branch::findOrFail($id);
        $countries = Country::where('is_active', 1)->get();
        $states = State::where('country_id', $branch->city->state->country_id)->where('is_active', 1)->get();
        $cities = City::where('state_id', $branch->city->state_id)->where('is_active', 1)->get();
        $systems = System::all();

        return view('admin.branch.edit', compact('branch', 'countries', 'states', 'cities', 'systems'));
    }

    /**
     * Update the specified branch in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!PermissionHelper::hasPermission('update', Branch::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'system_id' => 'required|exists:systems,id',
            'is_active' => 'nullable|boolean',
            'maximum_player_number' => 'nullable|integer|min:1',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'name_ur' => $request->name_ur,
            'city_id' => $request->city_id,
            'system_id' => $request->system_id,
            'address' => $request->address,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? false,
            'maximum_player_number' => $request->maximum_player_number ?? null,
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified branch from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (!PermissionHelper::hasPermission('delete', Branch::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }



public function players(Request $request, $branchId)
{
    $branch = Branch::with(['city.state.country', 'system'])->findOrFail($branchId);

    // 1) Refresh players.status for THIS branch (active if any program payment ends today or later)
    DB::affectingStatement("
        UPDATE players p
        LEFT JOIN (
            SELECT player_id, MAX(end_date) AS last_end
            FROM payments
            WHERE end_date IS NOT NULL
              AND category = 'program'
              AND status IN ('paid','partial')
            GROUP BY player_id
        ) x ON x.player_id = p.id
        SET p.status = CASE
            WHEN x.last_end IS NOT NULL AND DATE(x.last_end) >= CURDATE() THEN 'active'
            ELSE 'expired'
        END
        WHERE p.branch_id = ?
    ", [$branchId]);

    // 2) Base scope = players who have programs in this branch (for academy totals)
    $baseScope = Player::query()
        ->whereHas('programs', fn ($q) => $q->where('branch_id', $branchId));

    // 2.a) Per-academy totals for this branch (for the academy dropdown)
    $countsByAcademy = (clone $baseScope)
        ->selectRaw("
            academy_id,
            COUNT(*) AS total,
            SUM(CASE WHEN status = 'active'  THEN 1 ELSE 0 END) AS active,
            SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS expired
        ")
        ->groupBy('academy_id')
        ->get()
        ->keyBy('academy_id');

    $academies = Academy::where('branch_id', $branchId)
        ->select('id','name_en','name_ar')
        ->orderBy('name_en')
        ->get()
        ->map(function ($a) use ($countsByAcademy) {
            $c = $countsByAcademy->get($a->id);
            return [
                'id'      => $a->id,
                'name_en' => $a->name_en,
                'name_ar' => $a->name_ar,
                'total'   => $c->total   ?? 0,
                'active'  => $c->active  ?? 0,
                'expired' => $c->expired ?? 0,
            ];
        })
        ->values();

    // 3) Build the main listing query (with eager loads)
    $base = Player::query()
        ->with([
            'user:id,name,email',
            'sport:id,name_en,name_ar',
            'nationality:id,name_en,name_ar',
            'academy:id,name_en,name_ar',
            'branch:id,name',
            // limit eager-loaded programs to THIS branch to keep the list relevant
            'programs' => function ($q) use ($branchId) {
                $q->select('programs.id','name_en','name_ar','branch_id','academy_id')
                  ->where('branch_id', $branchId)
                  ->orderBy('name_en');
            },
            'payments' => function ($q) {
                $q->select('id','player_id','category','status','payment_method_id',
                           'payment_date','start_date','end_date','paid_amount','reset_number')
                  ->with(['paymentMethod:id,name,name_ar'])
                  ->orderByDesc('payment_date')->orderByDesc('id');
            },
        ])
        ->whereHas('programs', fn($q) => $q->where('branch_id', $branchId));

    // 4) Filters
    $academyId = $request->input('academy_id');
    if (!empty($academyId)) {
        $base->where('academy_id', $academyId);
    }

    $programId = $request->input('program_id');
    if (!empty($programId)) {
        $base->whereHas('programs', fn($q) => $q->where('programs.id', $programId));
    }

    $status = $request->input('status'); // 'active' | 'expired' | null
    if (in_array($status, ['active','expired'], true)) {
        $base->where('status', $status);
    }

    if ($request->filled('search')) {
        $search = trim((string) $request->input('search'));
        $base->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // 5) Chips (counts reflect CURRENT filters incl. academy & program)
    $activeCount  = (clone $base)->where('status', 'active')->count();
    $expiredCount = (clone $base)->where('status', 'expired')->count();

    // 6) Programs list for the program dropdown (depends on selected academy or all)
    $programs = Program::query()
        ->where('branch_id', $branchId)
        ->when($academyId, fn($q) => $q->where('academy_id', $academyId))
        ->select('id','name_en','name_ar','academy_id')
        ->orderBy('name_en')
        ->get();

    // 7) Export
    if ($request->input('export') === 'excel') {
        $rows = (clone $base)->orderByDesc('id')->get()->map(function (Player $p) {
            $sport = $p->sport
                ? (app()->getLocale()==='ar' ? $p->sport->name_ar : $p->sport->name_en)
                : '-';
            $nationality = $p->nationality
                ? (app()->getLocale()==='ar' ? $p->nationality->name_ar : $p->nationality->name_en)
                : '-';
            $academyName = $p->academy
                ? (app()->getLocale()==='ar' ? $p->academy->name_ar : $p->academy->name_en)
                : '-';
            $programNames = $p->programs->map(fn($pr) => app()->getLocale()==='ar' ? ($pr->name_ar ?? $pr->name_en) : $pr->name_en)->join(', ');

            return [
                'ID'              => $p->id,
                'Name'            => optional($p->user)->name,
                'Email'           => optional($p->user)->email,
                'Phone'           => $p->guardian_phone,
                'Branch'          => optional($p->branch)->name,
                'Academy'         => $academyName,
                'Programs'        => $programNames,
                'Sport'           => $sport,
                'Nationality'     => $nationality,
                'Gender'          => $p->gender,
                'Player Code'     => $p->player_code,
                'Birth Date'      => $p->birth_date,
                'Guardian Name'   => $p->guardian_name,
                'Guardian Phone'  => $p->guardian_phone,
                'Position'        => $p->position,
                'Level'           => $p->level,
                'Shirt Size'      => $p->shirt_size,
                'Shorts Size'     => $p->shorts_size,
                'Shoe Size'       => $p->shoe_size,
                'Medical Notes'   => $p->medical_notes,
                'Remarks'         => $p->remarks,
                'Status'          => $p->status,
                'Created At'      => optional($p->created_at)->format('Y-m-d'),
            ];
        });

        $filename = 'branch_players_'.$branchId.'_'.now()->format('Ymd_His');

        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            $export = new class($rows) implements
                \Maatwebsite\Excel\Concerns\FromArray,
                \Maatwebsite\Excel\Concerns\WithHeadings {
                private $rows;
                public function __construct($rows) { $this->rows = $rows; }
                public function array(): array   { return $this->rows->values()->toArray(); }
                public function headings(): array { return array_keys($this->rows->first() ?? []); }
            };
            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename.'.xlsx');
        }

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            if ($rows->isNotEmpty()) {
                fputcsv($out, array_keys($rows->first()));
                foreach ($rows as $r) fputcsv($out, array_values($r));
            }
            fclose($out);
        }, $filename.'.csv', ['Content-Type' => 'text/csv']);
    }

    // 8) Paginate & respond
    $players = $base->orderByDesc('id')->paginate(10);

    if ($request->ajax()) {
        $playersData = $players->map(function (Player $player) use ($branchId) {
            $programs = $player->programs->map(function ($pr) {
                return [
                    'id'   => $pr->id,
                    'name' => app()->getLocale()==='ar' ? ($pr->name_ar ?? $pr->name_en) : $pr->name_en,
                ];
            });

            $payments = $player->payments->map(function ($p) {
                return [
                    'id'           => $p->id,
                    'category'     => $p->category,
                    'status'       => $p->status,
                    'method'       => $p->paymentMethod
                        ? (app()->getLocale()==='ar'
                            ? ($p->paymentMethod->name_ar ?? $p->paymentMethod->name)
                            : $p->paymentMethod->name)
                        : '-',
                    'payment_date' => optional($p->payment_date)->format('Y-m-d'),
                    'start_date'   => optional($p->start_date)->format('Y-m-d'),
                    'end_date'     => optional($p->end_date)->format('Y-m-d'),
                    'paid_amount'  => (float) $p->paid_amount,
                    'reset'        => $p->reset_number,
                ];
            });

            return [
                'id'             => $player->id,
                'name'           => optional($player->user)->name ?? '',
                'email'          => optional($player->user)->email ?? '',
                'phone'          => $player->guardian_phone ?? '',
                'birth_date'     => $player->birth_date,
                'gender'         => $player->gender,
                'player_code'    => $player->player_code,
                'guardian_name'  => $player->guardian_name,
                'guardian_phone' => $player->guardian_phone,
                'position'       => $player->position,
                'level'          => $player->level,
                'shirt_size'     => $player->shirt_size,
                'shorts_size'    => $player->shorts_size,
                'shoe_size'      => $player->shoe_size,
                'medical_notes'  => $player->medical_notes,
                'remarks'        => $player->remarks,
                'sport'          => $player->sport
                    ? (app()->getLocale()==='ar' ? $player->sport->name_ar : $player->sport->name_en)
                    : '-',
                'nationality'    => $player->nationality
                    ? (app()->getLocale()==='ar' ? $player->nationality->name_ar : $player->nationality->name_en)
                    : '-',
                'academy'        => $player->academy
                    ? (app()->getLocale()==='ar' ? $player->academy->name_ar : $player->academy->name_en)
                    : '-',
                'branch'         => optional($player->branch)->name,
                'programs'       => $programs,
                'created_at'     => optional($player->created_at)->format('Y-m-d'),
                'status'         => $player->status ?? 'expired',
                'payments'       => $payments,
            ];
        });

        return response()->json([
            'players' => $playersData->values(),
            'pagination' => [
                'current_page' => $players->currentPage(),
                'last_page'    => $players->lastPage(),
                'total'        => $players->total(),
                'from'         => $players->firstItem() ?? 0,
            ],
            'counts' => [
                'active'  => $activeCount,
                'expired' => $expiredCount,
            ],
            'academies' => $academies,
            'programs'  => $programs->map(fn($p) => [
                'id'   => $p->id,
                'name' => app()->getLocale()==='ar' ? ($p->name_ar ?? $p->name_en) : $p->name_en,
            ])->values(),
        ]);
    }

    // Full page render (server counts + academies for initial UI)
    return view('admin.branch.players', compact(
        'branch', 'players', 'activeCount', 'expiredCount', 'academies'
    ));
}

}
