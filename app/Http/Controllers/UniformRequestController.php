<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Player;
use App\Models\System;
use App\Models\Item;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\UniformRequest;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PermissionHelper;

class UniformRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!PermissionHelper::hasPermission('view', UniformRequest::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $user = Auth::user();
        $systems = [];
        $branches = [];
        $players = [];
        $items = [];
        $currencies = Currency::pluck('code', 'id');
        $query = UniformRequest::with(['player.user', 'item', 'branch', 'system']);

        switch ($user->role) {
            case 'full_admin':
                $systems = System::pluck('name', 'id');

                if ($request->filled('system_id')) {
                    $query->where('system_id', $request->system_id);
                    $branches = Branch::where('system_id', $request->system_id)->pluck('name', 'id');

                    $players = Player::whereHas('branch', function ($q) use ($request) {
                        $q->where('system_id', $request->system_id);
                    })->with('user:id,name')->get();

                    $items = Item::where('system_id', $request->system_id)->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');
                }
                break;

            case 'branch_admin':
            case 'academy_admin':
            case 'coach':
            case 'player':
                if ($user->branch_id) {
                    $query->where('branch_id', $user->branch_id);
                    $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');

                    $players = Player::where('branch_id', $user->branch_id)->with('user:id,name')->get();
                    $items = Item::where('system_id', $user->system_id)->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');
                }
                break;
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $statusCounts = $query->clone() // clone to preserve original query
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $uniformRequests = $query->latest()->paginate(10);;

        return view('admin.uniform_requests.index', compact(
            'uniformRequests',
            'systems',
            'branches',
            'players',
            'items',
            'currencies',
            'request',
            'statusCounts'
        ));
    }


    public function create()
    {
        if (!PermissionHelper::hasPermission('create', UniformRequest::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $user = Auth::user();

        $systems = [];
        $branches = [];
        $players = [];
        $items = [];
        $currencies = Currency::all();

        switch ($user->role) {
            case 'full_admin':
                $systems = System::pluck('name', 'id');
                // Wait for system selection before loading branches/players/items via JS
                break;

            case 'system_admin':
                if ($user->system_id) {
                    $systems = System::where('id', $user->system_id)->pluck('name', 'id');
                    $branches = Branch::where('system_id', $user->system_id)->pluck('name', 'id');
                    $players = Player::whereHas('branch', function ($q) use ($user) {
                        $q->where('system_id', $user->system_id);
                    })->with('user:id,name')->get();
                    $items = Item::where('system_id', $user->system_id)
                        ->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');
                }
                break;

            case 'branch_admin':
            case 'academy_admin':
            case 'coach':
            case 'player':
                if ($user->branch_id && $user->system_id) {
                    $systems = System::where('id', $user->system_id)->pluck('name', 'id');
                    $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
                    $players = Player::where('branch_id', $user->branch_id)->with('user:id,name')->get();
                    $items = Item::where('system_id', $user->system_id)
                        ->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');
                }
                break;
        }

        return view('admin.uniform_requests.create', compact(
            'systems',
            'branches',
            'players',
            'items',
            'currencies'
        ));
    }



    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', UniformRequest::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $validated = $request->validate([
            'system_id'   => 'nullable|exists:systems,id',
            'branch_id'   => 'required|exists:branches,id',
            'player_id'   => 'required|exists:players,id',
            'item_id'     => 'required|exists:items,id',
            'size'        => 'required|string|max:50',
            'color'       => 'required|string|max:50',
            'quantity'    => 'required|integer|min:1',
            'amount'      => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'notes'       => 'nullable|string',
            //  'status'      => 'required|in:requested,ordered,delivered,cancelled',
        ]);

        $validated['requested_at'] = now();
        $validated['request_date'] = now();

        UniformRequest::create($validated);

        return redirect()->route('admin.uniform-requests.index')
            ->with('success', __('uniform_requests.created_successfully'));
    }


    public function edit($id)
    {
        if (!PermissionHelper::hasPermission('update', UniformRequest::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $user = Auth::user();
        $uniformRequest = UniformRequest::findOrFail($id);

        $systems = [];
        $branches = [];
        $players = [];
        $items = [];
        $currencies = Currency::all();

        switch ($user->role) {
            case 'full_admin':
                $systems = System::pluck('name', 'id');
                $branches = Branch::where('system_id', $uniformRequest->system_id)->pluck('name', 'id');
                $players = Player::whereHas('branch', function ($q) use ($uniformRequest) {
                    $q->where('system_id', $uniformRequest->system_id);
                })->with('user:id,name')->get();
                $items = Item::where('system_id', $uniformRequest->system_id)
                    ->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');
                break;

            case 'system_admin':
                if ($user->system_id) {
                    $systems = System::where('id', $user->system_id)->pluck('name', 'id');
                    $branches = Branch::where('system_id', $user->system_id)->pluck('name', 'id');
                    $players = Player::whereHas('branch', function ($q) use ($user) {
                        $q->where('system_id', $user->system_id);
                    })->with('user:id,name')->get();
                    $items = Item::where('system_id', $user->system_id)
                        ->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');
                }
                break;

            case 'branch_admin':
            case 'academy_admin':
            case 'coach':
            case 'player':
                if ($user->branch_id && $user->system_id) {
                    $systems = System::where('id', $user->system_id)->pluck('name', 'id');
                    $branches = Branch::where('id', $user->branch_id)->pluck('name', 'id');
                    $players = Player::where('branch_id', $user->branch_id)->with('user:id,name')->get();
                    $items = Item::where('system_id', $user->system_id)
                        ->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');
                }
                break;
        }

        return view('admin.uniform_requests.edit', compact(
            'uniformRequest',
            'systems',
            'branches',
            'players',
            'items',
            'currencies'
        ));
    }


    public function update(Request $request, $id)
    {
        if (!PermissionHelper::hasPermission('update', UniformRequest::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $uniformRequest = UniformRequest::findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'system_id'   => 'nullable|exists:systems,id',
            'branch_id'   => 'required|exists:branches,id',
            'player_id'   => 'required|exists:players,id',
            'item_id'     => 'required|exists:items,id',
            'size'        => 'required|string|max:50',
            'color'       => 'required|string|max:50',
            'quantity'    => 'required|integer|min:1',
            'amount'      => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'notes'       => 'nullable|string',
            'status'      => 'required|in:requested,ordered,delivered,cancelled',
        ]);
        if (in_array($user->role, ['full_admin', 'system_admin'])) {
            $validated['admin_remarks'] = $request->input('admin_remarks');
            $validated['approved_at'] = $request->input('approved_at');
            $validated['ordered_at'] = $request->input('ordered_at');
            $validated['delivered_at'] = $request->input('delivered_at');
        }

        $uniformRequest->update($validated);

        return redirect()->route('admin.uniform-requests.index')
            ->with('success', __('uniform_requests.updated_successfully'));
    }

    public function destroy($id, Request $request)
    {
        if (!PermissionHelper::hasPermission('delete', UniformRequest::MODEL_NAME)) {
            return response()->json(['message' => __('uniform_requests.unauthorized')], 403);
        }

        $uniformRequest = UniformRequest::findOrFail($id);
        $uniformRequest->delete();

        if ($request->ajax()) {
            return response()->json(['message' => __('uniform_requests.deleted_successfully')]);
        }

        return redirect()->route('admin.uniform-requests.index')
            ->with('success', __('uniform_requests.deleted_successfully'));
    }

}
