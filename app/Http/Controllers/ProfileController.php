<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $earnedAchievements = $user->userAchievements()
            ->with('achievement')
            ->latest('earned_at')
            ->get();

        return view('profile.index', [
            'user' => $user,
            'studentProfile' => $user->studentProfile,
            'earnedAchievements' => $earnedAchievements,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->update(['name' => $data['name'], 'email' => $data['email']]);

        if ($request->hasFile('avatar') && $user->studentProfile) {
            if ($user->studentProfile->avatar_path) {
                Storage::disk('public')->delete($user->studentProfile->avatar_path);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->studentProfile->update(['avatar_path' => $path]);
        }

        return back()->with('success', 'Profil muvaffaqiyatli yangilandi.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Joriy parol noto\'g\'ri.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Parol muvaffaqiyatli o\'zgartirildi.');
    }
}
