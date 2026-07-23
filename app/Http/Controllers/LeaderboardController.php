<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Get users ordered by XP descending
        // Join student_profiles to get XP
        $topUsers = User::whereHas('studentProfile')
            ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->orderBy('student_profiles.xp', 'desc')
            ->select('users.*', 'student_profiles.xp', 'student_profiles.level', 'student_profiles.streak_current as streak')
            ->limit(10)
            ->get();

        return view('leaderboard.index', compact('topUsers'));
    }
}
