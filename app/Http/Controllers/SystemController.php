<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class SystemController extends Controller
{
    // Show all systems
    public function index()
    {
        if (!PermissionHelper::hasPermission('view', System::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $systems = System::latest()->paginate(10);
        return view('admin.system.index', compact('systems'));
    }

    // Show form to create a new system
    public function create()
    {
        if (!PermissionHelper::hasPermission('view', System::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.system.create');
    }

    // Store new system
    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', System::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        System::create($request->only('name', 'description'));

        return redirect()->route('admin.systems.index')->with('success', 'System created successfully.');
    }

    // Show single system
    public function show(System $system)
    {
        if (!PermissionHelper::hasPermission('view', System::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.system.show', compact('system'));
    }

    // Show edit form
    public function edit(System $system)
    {
        if (!PermissionHelper::hasPermission('update', System::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.system.edit', compact('system'));
    }

    // Update the system
    public function update(Request $request, System $system)
    {
        if (!PermissionHelper::hasPermission('update', System::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $system->update($request->only('name', 'description'));

        return redirect()->route('admin.systems.index')->with('success', 'System updated successfully.');
    }

    // Delete the system
    public function destroy(System $system)
    {
        if (!PermissionHelper::hasPermission('delete', System::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $system->delete();
        return redirect()->route('admin.systems.index')->with('success', 'System deleted successfully.');
    }
}
