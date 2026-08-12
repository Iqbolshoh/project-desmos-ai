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
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ], [
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a JPG, PNG, WEBP or GIF file.',
            'avatar.max' => 'The avatar may not be larger than 2 MB.',
        ]);

        $user->update(['name' => $data['name'], 'email' => $data['email']]);

        if ($request->hasFile('avatar')) {
            // Admin and Google accounts may not have a profile row yet; without
            // this the upload used to be validated and then silently dropped.
            $profile = $user->studentProfile()->firstOrCreate([]);

            $path = $request->file('avatar')->store('avatars', 'public');

            if ($path === false) {
                return back()->withErrors(['avatar' => 'The avatar could not be saved. Please try again.']);
            }

            $previous = $profile->avatar_path;
            $profile->update(['avatar_path' => $path]);

            // Only drop the old file once the new one is safely recorded.
            if ($previous && $previous !== $path) {
                Storage::disk('public')->delete($previous);
            }
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Google-only accounts have no password yet, so there is nothing to
        // confirm — they are setting one for the first time.
        $hasPassword = filled($user->password);

        $request->validate([
            'current_password' => $hasPassword ? 'required' : 'nullable',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($hasPassword && ! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', $hasPassword
            ? 'Password changed successfully.'
            : 'Password set successfully. You can now sign in with your email too.');
    }
}
