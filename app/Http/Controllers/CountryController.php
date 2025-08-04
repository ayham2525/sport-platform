<?php
namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\FileUploader;
use App\Helpers\PermissionHelper;

class CountryController extends Controller
{
    public function index()
    {

         if (!PermissionHelper::hasPermission('view',Country::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $countries = Country::all();
        return view('admin.country.index', compact('countries'));
    }

    public function show(Country $country)
    {
        if (!PermissionHelper::hasPermission('view',Country::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.country.show', compact('country'));
    }

    public function create()
    {
        if (!PermissionHelper::hasPermission('create',Country::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.country.create');
    }

    public function edit(Country $country)
    {
        if (!PermissionHelper::hasPermission('update',Country::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.country.edit', compact('country'));
    }


    public function store(Request $request, FileUploader $uploader)
    {
        if (!PermissionHelper::hasPermission('create',Country::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_native' => 'nullable|string|max:255',
            'iso2' => 'required|size:2|unique:countries,iso2',
            'iso3' => 'nullable|size:3|unique:countries,iso3',
            'phone_code' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'flag' => 'nullable|mimes:jpg,jpeg,png,webp,svg,svg+xml|max:2048',
            'is_active' => 'nullable|in:1',
        ]);


        // Handle file upload
        $flagPath = null;
        if ($request->hasFile('flag')) {
            $flagPath = $uploader->uploadImage($request->file('flag'), 'countries');
        }

        Country::create(array_merge($validated, [
            'flag' => $flagPath,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]));

        return redirect()->route('admin.countries.index')->with('success', __('messages.created'));
    }

    public function update(Request $request, Country $country, FileUploader $uploader)
    {
        if (!PermissionHelper::hasPermission('update',Country::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_native' => 'nullable|string|max:255',
            'iso2' => 'required|size:2|unique:countries,iso2,' . $country->id,
            'iso3' => 'nullable|size:3|unique:countries,iso3,' . $country->id,
            'phone_code' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'flag' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'is_active' => 'nullable|in:1',
        ]);

        if ($request->hasFile('flag')) {
            // Delete old flag
            if ($country->flag) {
                $uploader->deleteImage($country->flag);
            }
            $validated['flag'] = $uploader->uploadImage($request->file('flag'), 'countries');
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $country->update($validated);

        return redirect()->route('admin.countries.index')->with('success', __('messages.updated'));
    }

    public function destroy(Country $country, FileUploader $uploader)
    {
        if (!PermissionHelper::hasPermission('delete',Country::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        if ($country->flag) {
            $uploader->deleteImage($country->flag);
        }

        $country->delete();

        return redirect()->route('admin.countries.index')->with('success', __('messages.deleted'));
    }
}
