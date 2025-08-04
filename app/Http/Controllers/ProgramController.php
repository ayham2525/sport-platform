<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Branch;
use App\Models\Player;
use App\Models\System;
use App\Models\Academy;
use App\Models\Country;
use App\Models\Program;
use App\Models\ProgramDay;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class ProgramController extends Controller
{
   public function index(Request $request)
    {
        if (!PermissionHelper::hasPermission('view', Program::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user = auth()->user();

        $query = Program::with(['system', 'branch', 'academy']);

        // Apply role-based access control
        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id) {
                    $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                    $academyIds = Academy::whereIn('branch_id', $branchIds)->pluck('id')->toArray();
                    $query->where('system_id', $user->system_id)
                        ->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'branch_admin':
                if ($user->branch_id) {
                    $academyIds = Academy::where('branch_id', $user->branch_id)->pluck('id')->toArray();
                    $query->where('branch_id', $user->branch_id)
                        ->whereIn('academy_id', $academyIds);
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
                // full_admin and other roles get all programs
                break;
        }

        // Apply filters
        if ($request->filled('search')) {
            $query->where('name_en', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('system_id')) {
            $query->where('system_id', $request->system_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('academy_id')) {
            $query->where('academy_id', $request->academy_id);
        }

        $programs = $query->latest()->paginate(10);

        // Prepare dropdown filters based on role
        switch ($user->role) {
            case 'system_admin':
                $systems = System::where('id', $user->system_id)->get();
                $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                $branches = Branch::whereIn('id', $branchIds)->get();
                $academies = Academy::whereIn('branch_id', $branchIds)->get();
                break;

            case 'branch_admin':
                $branches = Branch::where('id', $user->branch_id)->get();
                $systems = System::whereHas('branches', fn ($q) => $q->where('id', $user->branch_id))->get();
                $academies = Academy::where('branch_id', $user->branch_id)->get();
                break;

            case 'academy_admin':
            case 'coach':
            case 'player':
                $academyIds = is_array($user->academy_id)
                    ? $user->academy_id
                    : json_decode($user->academy_id, true) ?? [];

                $academies = Academy::whereIn('id', $academyIds)->get();
                $branchIds = $academies->pluck('branch_id')->unique();
                $branches = Branch::whereIn('id', $branchIds)->get();
                $systems = System::whereIn('id', Branch::whereIn('id', $branchIds)->pluck('system_id')->unique())->get();
                break;

            default:
                $systems = System::all();
                $branches = Branch::all();
                $academies = Academy::all();
                break;
        }

        return view('admin.programs.index', compact('programs', 'systems', 'branches', 'academies'));
    }


    public function create()
    {
        if (!PermissionHelper::hasPermission('create', Program::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user = auth()->user();

        // 1. Systems
        if (in_array($user->role, ['system_admin', 'branch_admin', 'academy_admin', 'coach', 'player'])) {
            $systems = System::where('id', $user->system_id)->get();
        } else {
            $systems = System::all();
        }

        // 2. Branches
        $branches = Branch::query();
        if ($user->role === 'system_admin') {
            $branches->where('system_id', $user->system_id);
        } elseif (in_array($user->role, ['branch_admin', 'academy_admin', 'coach', 'player'])) {
            $branches->where('id', $user->branch_id);
        }
        $branches = $branches->get();

        // 3. Academies
        $academies = Academy::query();
        if ($user->role === 'system_admin') {
            $academies->whereHas('branch', function ($q) use ($user) {
                $q->where('system_id', $user->system_id);
            });
        } elseif ($user->role === 'branch_admin') {
            $academies->where('branch_id', $user->branch_id);
        } elseif (in_array($user->role, ['academy_admin', 'coach', 'player'])) {
            $rawAcademyId = $user->academy_id;

            if (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
                $academyIds = json_decode($rawAcademyId, true) ?? [];
            } elseif (is_array($rawAcademyId)) {
                $academyIds = $rawAcademyId;
            } elseif (!is_null($rawAcademyId)) {
                $academyIds = [$rawAcademyId];
            } else {
                $academyIds = [];
            }

            $academyIds = array_filter(array_map('intval', $academyIds));
            $academies->whereIn('id', $academyIds);
        }
        $academies = $academies->get();

        // 4. Other data
        $countries = Country::all(); // Needed for dynamic city loading

        return view('admin.programs.create', compact(
            'systems',
            'branches',
            'academies',
            'countries'
        ));
    }


    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', Program::MODEL_NAME)) {
         return PermissionHelper::denyAccessResponse();
         }
        $request->validate([
            'system_id' => 'required',
            'branch_id' => 'required',
            'academy_id' => 'required',
            'name_en' => 'required',
            'class_count' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $program = Program::create($request->except('days'));

        if ($request->has('days')) {
            foreach ($request->days as $day) {
                ProgramDay::create([
                    'program_id' => $program->id,
                    'day' => $day
                ]);
            }
        }

        return redirect()->route('admin.programs.index')->with('success', __('Program created successfully.'));
    }

    public function show(Program $program)
    {
         if (!PermissionHelper::hasPermission('view', Program::MODEL_NAME)) {
         return PermissionHelper::denyAccessResponse();
         }
        $program->load('days', 'classes', 'system', 'branch', 'academy');
        return view('admin.programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        if (!PermissionHelper::hasPermission('update', Program::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user = auth()->user();

        // 1. Systems
        if (in_array($user->role, ['system_admin', 'branch_admin', 'academy_admin', 'coach', 'player'])) {
            $systems = System::where('id', $user->system_id)->get();
        } else {
            $systems = System::all();
        }

        // 2. Branches
        $branches = Branch::query();
        if ($user->role === 'system_admin') {
            $branches->where('system_id', $user->system_id);
        } elseif (in_array($user->role, ['branch_admin', 'academy_admin', 'coach', 'player'])) {
            $branches->where('id', $user->branch_id);
        }
        $branches = $branches->get();

        // 3. Academies
        $academies = Academy::query();
        if ($user->role === 'system_admin') {
            $academies->whereHas('branch', function ($q) use ($user) {
                $q->where('system_id', $user->system_id);
            });
        } elseif ($user->role === 'branch_admin') {
            $academies->where('branch_id', $user->branch_id);
        } elseif (in_array($user->role, ['academy_admin', 'coach', 'player'])) {
            $rawAcademyId = $user->academy_id;

            if (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
                $academyIds = json_decode($rawAcademyId, true) ?? [];
            } elseif (is_array($rawAcademyId)) {
                $academyIds = $rawAcademyId;
            } elseif (!is_null($rawAcademyId)) {
                $academyIds = [$rawAcademyId];
            } else {
                $academyIds = [];
            }

            $academyIds = array_filter(array_map('intval', $academyIds));
            $academies->whereIn('id', $academyIds);
        }
        $academies = $academies->get();

        // 4. Country, State, City
        $countries = Country::all();
        $city = optional($program->branch)->city;
        $state = optional($city)->state;
        $states = $state ? State::where('country_id', $state->country_id)->get() : collect();
        $cities = $state ? City::where('state_id', $state->id)->get() : collect();

        return view('admin.programs.edit', compact(
            'program', 'systems', 'branches', 'academies',
            'countries', 'states', 'cities'
        ));
    }



    public function update(Request $request, Program $program)
    {
         if (!PermissionHelper::hasPermission('update', Program::MODEL_NAME)) {
         return PermissionHelper::denyAccessResponse();
         }
        $program->update($request->except('days'));

        $program->days()->delete();
        if ($request->has('days')) {
            foreach ($request->days as $day) {
                ProgramDay::create([
                    'program_id' => $program->id,
                    'day' => $day
                ]);
            }
        }

        return redirect()->route('admin.programs.index')->with('success', __('Program updated successfully.'));
    }

    public function destroy(Program $program)
    {
         if (!PermissionHelper::hasPermission('delete', Program::MODEL_NAME)) {
         return PermissionHelper::denyAccessResponse();
         }
        $program->delete();
        return redirect()->back()->with('success', __('Program deleted.'));
    }

    public function players(Program $program)
    {
        $players = Player::whereHas('programs', function ($q) use ($program) {
            $q->where('program_id', $program->id);
        })->with('user', 'branch', 'academy', 'sport')->paginate(20);

        return view('admin.programs.players', compact('program', 'players'));
    }

}
