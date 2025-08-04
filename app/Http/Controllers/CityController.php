<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class CityController extends Controller
{
    public function index(Request $request)
    {

        if (!PermissionHelper::hasPermission('view', City::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $query = City::with('state.country');

        if ($request->filled('country_id')) {
            $query->whereHas('state', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $cities = $query->latest()->get();
        $countries = Country::where('is_active', 1)->get();
        $states = collect();

        if ($request->filled('country_id')) {
            $states = State::where('country_id', $request->country_id)->where('is_active', 1)->get();
        }

        return view('admin.city.index', compact('cities', 'countries', 'states'));
    }


    public function create()
    {

        if (!PermissionHelper::hasPermission('create', City::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $countries = Country::where('is_active', 1)->get();
        $states = State::where('is_active', 1)->get();
        return view('admin.city.create', compact('countries' , 'states'));
    }

    public function store(Request $request)
    {

        if (!PermissionHelper::hasPermission('create', City::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'is_active' => 'nullable|boolean',
        ]);

        City::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'City created successfully.');
    }

    public function show($id)
    {
        if (!PermissionHelper::hasPermission('view', City::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $city = City::with('state.country')->findOrFail($id);
        return view('admin.city.show', compact('city'));
    }

    public function edit($id)
    {
        if (!PermissionHelper::hasPermission('update', City::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $city = City::findOrFail($id);
        $countries = Country::where('is_active', 1)->get();
        $states = State::where('country_id', $city->state->country_id)->where('is_active', 1)->get();
        return view('admin.city.edit', compact('city', 'states', 'countries'));
    }

    public function update(Request $request, $id)
    {
        if (!PermissionHelper::hasPermission('update', City::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'is_active' => 'nullable|boolean',
        ]);

        $city = City::findOrFail($id);
        $city->update([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy($id)
    {
        if (!PermissionHelper::hasPermission('delete', City::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully.');
    }

}
