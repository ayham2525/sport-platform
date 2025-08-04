<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

class PermissionHelper
{
    public static function hasPermission(string $action, string $model): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Full admin always allowed
        if ($user->role === 'full_admin') {
            return true;
        }



            self::refreshSessionPermissions($user);
            $permissions = session('permissions', []);


        // Check permission
        foreach ($permissions as $permission) {
            if (
                isset($permission['action'], $permission['model']) &&
                $permission['action'] === $action &&
                $permission['model'] === $model
            ) {
                return true;
            }
        }

        return false;
    }

    public static function refreshSessionPermissions($user = null): void
    {
        $user = $user ?: Auth::user();

        if (!$user || $user->role === 'full_admin') {
            return;
        }

        $roleModel = $user->roleRelation;

        if ($roleModel) {
            $permissions = Permission::with('model')
                ->where('role_id', $roleModel->id)
                ->get()
                ->map(function ($permission) {
                    return [
                        'action' => $permission->action,
                        'model' => optional($permission->model)->name,
                    ];
                })
                ->toArray();

            session(['permissions' => $permissions]);
        }
    }

    public static function denyAccessResponse()
    {
        return response()->view('errors.access_denied', [], 403);
    }
}
