<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!PermissionHelper::hasPermission('view', State::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $states = State::with('country')->get();
        return view('admin.state.index', compact('states'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!PermissionHelper::hasPermission('view', State::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $state = State::with('country')->findOrFail($id);
        return view('admin.state.show', compact('state'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!PermissionHelper::hasPermission('create', State::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $countries = Country::where('is_active', 1)->get();
        return view('admin.state.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', State::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'is_active' => 'nullable|boolean',
        ]);

        State::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.states.index')->with('success', __('messages.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!PermissionHelper::hasPermission('update', State::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $state = State::findOrFail($id);
        $countries = Country::where('is_active', 1)->get();
        return view('admin.state.edit', compact('state', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!PermissionHelper::hasPermission('update', State::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'is_active' => 'nullable|boolean',
        ]);

        $state = State::findOrFail($id);
        $state->update([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.states.index')->with('success', __('messages.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!PermissionHelper::hasPermission('delete', State::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $state = State::findOrFail($id);
        $state->delete();

        return redirect()->route('admin.states.index')->with('success', __('messages.deleted'));
    }

    /**
     * Get states by country.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getStatesByCountry(Request $request)
    {
        $states = State::where('country_id', $request->country_id)
            ->where('is_active', 1)
            ->select('id', 'name')
            ->get();

        return response()->json($states);
    }
}
