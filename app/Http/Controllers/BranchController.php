<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Branch;
use App\Models\Player;
use App\Models\System;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

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
        $query = Branch::with('city.state.country', 'system');

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

        $branches = $query->latest()->get();
        $countries = Country::where('is_active', 1)->get();
        $states = $request->filled('country_id') ? State::where('country_id', $request->country_id)->where('is_active', 1)->get() : collect();
        $cities = $request->filled('state_id') ? City::where('state_id', $request->state_id)->where('is_active', 1)->get() : collect();
        $systems = System::all();

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

        $query = Player::with(['user', 'sport'])
            ->whereHas('programs', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

        // Optional search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $players = $query->paginate(10); // Only paginate once

        // If AJAX request (for JS search)
        if ($request->ajax()) {
            $playersData = $players->map(function ($player) {
                return [
                    'id' => $player->id,
                    'name' => $player->user->name ?? '',
                    'email' => $player->user->email ?? '',
                    'birth_date' => $player->birth_date,
                    'sport' => $player->sport
                        ? (app()->getLocale() === 'ar'
                            ? $player->sport->name_ar
                            : $player->sport->name_en)
                        : '-',
                    'created_at' => $player->created_at->format('Y-m-d'),
                ];
            });

            return response()->json([
                'players' => $playersData,
                'pagination' => [
                    'current_page' => $players->currentPage(),
                    'last_page' => $players->lastPage(),
                    'total' => $players->total(),
                ],
            ]);
        }

        // For full page view with Blade
        return view('admin.branch.players', compact('branch', 'players'));
    }
}
