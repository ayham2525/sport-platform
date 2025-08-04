<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\System;
use App\Models\Permission;
use App\Models\ModelEntity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\PermissionHelper;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if (!PermissionHelper::hasPermission('view', Permission::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        // Load role and model
        $query = Permission::with(['role', 'model']);

        if ($request->filled('search')) {
            $query->where('action', 'like', '%' . $request->search . '%');
        }

        $paginated = $query->latest()->paginate(20);

        $grouped = $paginated->getCollection()->groupBy(function ($item) {
            return $item->role->name ?? 'Unknown Role';
        });

        return view('admin.permissions.index', [
            'paginated' => $paginated,
            'grouped' => $grouped,
        ]);
    }


    public function create()
    {
        if (!PermissionHelper::hasPermission('create', Permission::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $roles = Role::all();
        $models = ModelEntity::all();
        $systems = System::all();
        $actions = ['view', 'create', 'update', 'delete', 'export', 'download'];
        return view('admin.permissions.create', compact('roles', 'models', 'actions', 'systems'));
    }

    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', Permission::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'model_id' => 'required|exists:models,id',
            'action'   => 'required|array|min:1',
            'action.*' => 'string|distinct',
        ]);

        foreach ($validated['action'] as $action) {
            Permission::firstOrCreate([
                'role_id'  => $validated['role_id'],
                'model_id' => $validated['model_id'],
                'action'   => $action,
            ]);
        }

        return redirect()->route('admin.permissions.index')->with('success', __('messages.created_successfully'));
    }

    public function show(Permission $permission)
    {
        if (!PermissionHelper::hasPermission('view', Permission::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {

        if (!PermissionHelper::hasPermission('update', Permission::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        // Fetch the system from the related role
        $systemId = optional($permission->role)->system_id;

        // Get all systems to populate the system dropdown
        $systems = System::all();

        // Roles (optionally filtered by system)
        $roles = Role::where('system_id', $systemId)->get();

        // Models and available actions
        $models = ModelEntity::all();
        $actions = ['view', 'create', 'update', 'delete', 'export', 'download'];

        return view('admin.permissions.edit', compact('permission', 'systems', 'roles', 'models', 'actions'));
    }


    public function update(Request $request, Permission $permission)
    {
        if (!PermissionHelper::hasPermission('update', Permission::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        // If no actions submitted, delete the permission
        if (!$request->has('action') || empty($request->action)) {
            $permission->delete();

            return redirect()->route('admin.permissions.index')
                ->with('success', __('messages.deleted_empty_permission'));
        }

        // Validate normally
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'model_id' => 'required|exists:models,id',
            'action' => 'required|array|min:1',
            'action.*' => [
                'string',
                Rule::in(['view', 'create', 'update', 'delete', 'export', 'download']),
            ],
        ]);

        // Check if a similar combination exists
        $exists = Permission::where('role_id', $request->role_id)
            ->where('model_id', $request->model_id)
            ->where('id', '!=', $permission->id)
            ->where(function ($q) use ($request) {
                foreach ($request->action as $action) {
                    $q->orWhereRaw("FIND_IN_SET(?, action)", [$action]);
                }
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['action' => __('messages.duplicate_permission')])->withInput();
        }

        // Update with new values
        $permission->update([
            'role_id' => $request->role_id,
            'model_id' => $request->model_id,
            'action' => implode(',', $request->action),
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', __('messages.updated_successfully'));
    }
    public function destroy(Permission $permission)
    {
        if (!PermissionHelper::hasPermission('delete', Permission::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', __('messages.deleted_successfully'));
    }
}
