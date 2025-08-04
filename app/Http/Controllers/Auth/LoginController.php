<?php
namespace App\Http\Controllers\Auth;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // path to your login Blade
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        // Save the user's role in the session
        $user = Auth::user();
        $request->session()->put('user_role', $user->role);

        if ($user->role !== 'full_admin') {
    $roleModel = $user->roleRelation;

    if ($roleModel) {
        $permissions = Permission::with('model')
            ->where('role_id', $roleModel->id)
            ->get()
            ->map(function ($permission) {
                return [
                    'action' => $permission->action,
                    'model' => $permission->model ? $permission->model->name : null,
                ];
            })
            ->toArray();

        $request->session()->put('permissions', $permissions);
    }
}

        return redirect()->intended('admin/dashboard'); // redirect to intended page or dashboard
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
