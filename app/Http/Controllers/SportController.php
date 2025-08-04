<?php


namespace App\Http\Controllers;

use App\Models\Sport;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class SportController extends Controller
{
    public function index()
    {
        if (!PermissionHelper::hasPermission('view', Sport::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $sports = Sport::latest()->paginate(20);
        return view('admin.sports.index', compact('sports'));
    }

    public function create()
    {
        if (!PermissionHelper::hasPermission('create', Sport::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.sports.create');
    }

    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', Sport::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name_en' => 'required|string|max:255|unique:sports,name_en',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Sport::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sports.index')->with('success', __('Sport created successfully.'));
    }

    public function edit(Sport $sport)
    {
        if (!PermissionHelper::hasPermission('update', Sport::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.sports.edit', compact('sport'));
    }

    public function update(Request $request, Sport $sport)
    {
        if (!PermissionHelper::hasPermission('update', Sport::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name_en' => 'required|string|max:255|unique:sports,name_en,' . $sport->id,
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $sport->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sports.index')->with('success', __('Sport updated successfully.'));
    }

    public function destroy(Sport $sport)
    {
        if (!PermissionHelper::hasPermission('delete', Sport::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $sport->delete();
        return redirect()->route('admin.sports.index')->with('success', __('Sport deleted successfully.'));
    }
}
