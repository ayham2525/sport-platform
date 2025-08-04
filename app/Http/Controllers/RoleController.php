<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\PermissionHelper;

class RoleController extends Controller
{
   public function index(Request $request)
    {
        if (!PermissionHelper::hasPermission('view', Role::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $query = Role::with('system');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('system_id')) {
            $query->where('system_id', $request->system_id);
        }

        $roles = $query->get();
        $systems = System::all();

        return view('admin.role.index', compact('roles', 'systems'));
    }


    public function show(Role $role)
    {
        if (!PermissionHelper::hasPermission('view', Role::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $role->load('system'); // eager load system relation
        return view('admin.role.show', compact('role'));
    }

    public function create()
    {
        if (!PermissionHelper::hasPermission('create', Role::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $systems = System::all();
        return view('admin.role.create', compact('systems'));
    }


    public function edit(Role $role)
    {
        if (!PermissionHelper::hasPermission('update', Role::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $systems = System::all();
        return view('admin.role.edit', compact('role', 'systems'));
    }

    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', Role::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                // Composite unique check (slug + system_id)
                Rule::unique('roles')->where(function ($query) use ($request) {
                    return $query->where('slug', $request->slug)
                                ->where('system_id', $request->system_id);
                }),
            ],
            'description' => 'nullable|string|max:1000',
            'system_id' => 'nullable|exists:systems,id',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')->with('success', __('messages.created_successfully'));
    }



    public function update(Request $request, Role $role)
    {
        if (!PermissionHelper::hasPermission('update', Role::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')
                    ->where(function ($query) use ($request, $role) {
                        return $query->where('system_id', $request->system_id);
                    })
                    ->ignore($role->id),
            ],
            'description' => 'nullable|string|max:1000',
            'system_id' => 'nullable|exists:systems,id',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Role $role)
    {

        if (!PermissionHelper::hasPermission('delete', Role::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('messages.deleted_successfully'));
    }

    public function getRolesBySystem($system_id)
    {
        $roles = Role::where('system_id', $system_id)->pluck('name', 'id');
        return response()->json($roles);
    }
}
