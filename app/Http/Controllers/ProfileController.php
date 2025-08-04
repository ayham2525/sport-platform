<?php
namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
public function show()
{
    $user = Auth::user();
    $evaluations = [];

    if (in_array($user->role, ['coach', 'player'])) {
        $evaluations = $user->receivedCoachEvaluations()
            ->with(['evaluation', 'responses.criteria'])
            ->latest()
            ->get();
    }

    return view('admin.profile.show', compact('user', 'evaluations'));
}

   public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', __('Account updated successfully.'));
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        // Remove old image if exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store new image
        $path = $request->file('profile_image')->store('profile_images', 'public');

        // Update DB record
        $user->profile_image = $path;
        $user->save();

        return back()->with('success', __('Profile image updated.'));
    }


   
}
