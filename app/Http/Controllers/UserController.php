<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Hash, Mail, Session
};
use Illuminate\Support\Str;
use App\Mail\UserWelcomeMail;
use App\Helpers\PermissionHelper;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        if (!PermissionHelper::hasPermission('view',User::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $users = User::with('system')->latest()->paginate(10);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        if (!PermissionHelper::hasPermission('create', User::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $systems = System::with('branches.academies')->get();
        $roles = Role::all();

        return view('users.create', compact('systems', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
         if (!PermissionHelper::hasPermission('uodate', User::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:full_admin,system_admin,branch_admin,academy_admin,employee,coach,player',
            'language' => 'nullable|in:en,ar',
            'system_id' => 'nullable|exists:systems,id',
            'password' => 'nullable|string|min:6',
        ]);

        $validated['branch_id'] = $request->input('branch_id');
        $validated['academy_id'] = $request->has('academy_id') ? json_encode($request->academy_id) : null;

        $password = $validated['password'] ?? Str::random(10);
        $validated['password'] = Hash::make($password);

        $user = User::create($validated);

        Mail::to($user->email)->send(new UserWelcomeMail($user, $password));

        return redirect()->route('admin.users.index')->with('success', __('messages.created'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
     if (!PermissionHelper::hasPermission('update', User::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $systems = System::all();
        $roles = Role::orderBy('name')
            ->when($user->system_id, fn($q) => $q->where('system_id', $user->system_id))
            ->get();

        return view('users.edit', compact('user', 'systems', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
         if (!PermissionHelper::hasPermission('update', User::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }


        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:full_admin,system_admin,branch_admin,academy_admin,employee,coach,player',
            'language' => 'nullable|string|max:191',
            'system_id' => 'nullable|exists:systems,id',
            'branch_id'  => 'nullable|exists:branches,id',
            'academy_id' => 'nullable',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', __('messages.updated'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
         if (!PermissionHelper::hasPermission('delete', User::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', __('messages.deleted'));
    }

    /**
     * Handle AJAX-based user filtering.
     */
    public function ajaxSearch(Request $request)
    {
        $query = User::with('system');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10);

        return response()->json([
            'html' => view('users.partials.table', compact('users'))->render(),
        ]);
    }
}
