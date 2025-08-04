<?php

namespace App\Http\Controllers;

use App\Models\ModelEntity;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\PermissionHelper;

class ModelEntityController extends Controller
{
    public function index(Request $request)
    {
        if (!PermissionHelper::hasPermission('view', ModelEntity::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $query = ModelEntity::with('system');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        $models = $query->latest()->paginate(10);
        return view('admin.models.index', compact('models'));
    }

    public function create()
    {
        if (!PermissionHelper::hasPermission('create', ModelEntity::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $systems = System::all();
        return view('admin.models.create', compact('systems'));
    }

    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', ModelEntity::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('models')->where(function ($query) use ($request) {
                    return $query->where('system_id', $request->system_id);
                }),
            ],
            'description' => 'nullable|string',
            'system_id' => 'required|exists:systems,id',
            'only_admin' => 'nullable|boolean',
        ]);

        ModelEntity::create($validated);

        return redirect()->route('admin.models.index')->with('success', __('messages.created_successfully'));
    }

    public function show(ModelEntity $model)
    {
        if (!PermissionHelper::hasPermission('view', ModelEntity::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $model->load('system');
        return view('admin.models.show', compact('model'));
    }

    public function edit(ModelEntity $model)
    {
        if (!PermissionHelper::hasPermission('update', ModelEntity::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $systems = System::all();
        return view('admin.models.edit', compact('model', 'systems'));
    }

    public function update(Request $request, ModelEntity $model)
    {
        if (!PermissionHelper::hasPermission('update', ModelEntity::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('models')->where(function ($query) use ($request) {
                    return $query->where('system_id', $request->system_id);
                })->ignore($model->id),
            ],
            'description' => 'nullable|string',
            'system_id' => 'required|exists:systems,id',
            'only_admin' => 'nullable|boolean',
        ]);

        $model->update($validated);

        return redirect()->route('admin.models.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy(ModelEntity $model)
    {
        if (!PermissionHelper::hasPermission('delete', ModelEntity::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $model->delete();
        return redirect()->route('admin.models.index')->with('success', __('messages.deleted_successfully'));
    }
}
