<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;
use App\Exports\PlayersQueryExport;
use Maatwebsite\Excel\Facades\Excel;

class AcademyController extends Controller
{
    /**
     * Normalize an "IDs" field to an int[] no matter the input type:
     * - array -> cleaned int[]
     * - JSON string -> decoded then cleaned
     * - comma-separated string -> split then cleaned
     * - numeric -> [int]
     * - null/empty -> []
     */
    private function normalizeIdArray($value): array
    {
        if (is_array($value)) {
            return array_values(array_unique(array_map(
                'intval',
                array_filter($value, fn ($v) => $v !== null && $v !== '' && is_numeric($v))
            )));
        }

        if ($value === null || $value === '') {
            return [];
        }

        if (is_numeric($value)) {
            return [intval($value)];
        }

        if (is_string($value)) {
            $trim = trim($value);
            if ($trim === '') {
                return [];
            }
            // Try JSON first
            $decoded = json_decode($trim, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->normalizeIdArray($decoded);
            }
            // Fallback: comma-separated
            $parts = preg_split('/\s*,\s*/', $trim, -1, PREG_SPLIT_NO_EMPTY);
            return $this->normalizeIdArray($parts ?: []);
        }

        return [];
    }

    /*
     * Display a listing of the academies.
     */
    public function index(Request $request)
    {
        if (!PermissionHelper::hasPermission('view', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user = auth()->user();
        $query = Academy::with('branch');
        $branches = collect();

        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id) {
                    $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                    $query->whereIn('branch_id', $branchIds);
                    $branches = Branch::whereIn('id', $branchIds)->get();
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'branch_admin':
                if ($user->branch_id) {
                    $query->where('branch_id', $user->branch_id);
                    $branches = Branch::where('id', $user->branch_id)->get();
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'academy_admin':
            case 'coach':
            case 'player':
                // ✅ Fix: normalize instead of json_decode() directly
                $academyIds = $this->normalizeIdArray($user->academy_id);
                if (!empty($academyIds)) {
                    $query->whereIn('id', $academyIds);
                    $branchIds = Academy::whereIn('id', $academyIds)->pluck('branch_id')->unique();
                    $branches = Branch::whereIn('id', $branchIds)->get();
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            default: // full_admin
                $branches = Branch::all();
                break;
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name_en', 'like', '%' . $request->search . '%')
                  ->orWhere('name_ar', 'like', '%' . $request->search . '%')
                  ->orWhere('name_ur', 'like', '%' . $request->search . '%');
            });
        }

        $academies = $query->latest()->paginate(10);

        return view('admin.academy.index', compact('academies', 'branches'));
    }

    /*
     * Show the form for creating a new academy.
     */
    public function create()
    {
        if (!PermissionHelper::hasPermission('create', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $branches = Branch::all();
        return view('admin.academy.create', compact('branches'));
    }

    /*
     * Store a newly created academy.
     */
    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'branch_id'     => 'required|exists:branches,id',
            'name_en'       => 'required|string|max:255',
            'name_ar'       => 'nullable|string|max:255',
            'name_ur'       => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'phone'         => 'nullable|string|max:20',
            'is_active'     => 'nullable|boolean',
        ]);

        Academy::create([
            'branch_id'      => $request->branch_id,
            'name_en'        => $request->name_en,
            'name_ar'        => $request->name_ar,
            'name_ur'        => $request->name_ur,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'description_ur' => $request->description_ur,
            'contact_email'  => $request->contact_email,
            'phone'          => $request->phone,
            'is_active'      => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.academies.index')->with('success', 'Academy created successfully.');
    }

    /*
     * Display the specified academy.
     */
    public function show($id)
    {
        if (!PermissionHelper::hasPermission('view', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $academy = Academy::with('branch')->findOrFail($id);
        return view('admin.academy.show', compact('academy'));
    }

    /*
     * Show the form for editing the specified academy.
     */
    public function edit($id)
    {
        if (!PermissionHelper::hasPermission('update', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $academy  = Academy::findOrFail($id);
        $branches = Branch::all();

        return view('admin.academy.edit', compact('academy', 'branches'));
    }

    /*
     * Update the specified academy.
     */
    public function update(Request $request, $id)
    {
        if (!PermissionHelper::hasPermission('update', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'branch_id'     => 'required|exists:branches,id',
            'name_en'       => 'required|string|max:255',
            'name_ar'       => 'nullable|string|max:255',
            'name_ur'       => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'phone'         => 'nullable|string|max:20',
            'is_active'     => 'nullable|boolean',
        ]);

        $academy = Academy::findOrFail($id);
        $academy->update([
            'branch_id'      => $request->branch_id,
            'name_en'        => $request->name_en,
            'name_ar'        => $request->name_ar,
            'name_ur'        => $request->name_ur,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'description_ur' => $request->description_ur,
            'contact_email'  => $request->contact_email,
            'phone'          => $request->phone,
            'is_active'      => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.academies.index')->with('success', 'Academy updated successfully.');
    }

    /*
     * Remove the specified academy.
     */
    public function destroy($id)
    {
        if (!PermissionHelper::hasPermission('delete', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $academy = Academy::findOrFail($id);
        $academy->delete();

        return redirect()->route('admin.academies.index')->with('success', 'Academy deleted successfully.');
    }

    public function players(Request $request, $id)
    {
        if (!PermissionHelper::hasPermission('view', Academy::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $academy = Academy::findOrFail($id);

        // Build query (latest payment for each)
        $query = $academy->players()->with([
            'user',
            'payments' => function ($q) {
                $q->latest('payment_date')->limit(1);
            },
        ]);

        if ($request->filled('status') && in_array($request->status, ['active','expired'], true)) {
            $query->where('status', $request->status);
        }

        // Counts
        $activeCount   = $academy->players()->where('status', 'active')->count();
        $inactiveCount = $academy->players()->where('status', 'expired')->count();

        $players = $query->paginate(15);

        return view('admin.academy.players', compact('academy', 'players', 'activeCount', 'inactiveCount'));
    }

    public function exportPlayers(Request $request)
    {
        return Excel::download(
            new PlayersQueryExport($request->get('status')),
            'players.xlsx'
        );
    }
}
