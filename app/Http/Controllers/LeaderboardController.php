<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Display paginated top students leaderboard ordered by XP.
     */
    public function index(): View
    {
        $topUsers = User::with('studentProfile')
            ->whereHas('studentProfile')
            ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->orderByDesc('student_profiles.xp')
            ->select('users.*', 'student_profiles.xp', 'student_profiles.level', 'student_profiles.streak_current as streak')
            ->paginate(15);

        return view('leaderboard.index', compact('topUsers'));
    }
}
