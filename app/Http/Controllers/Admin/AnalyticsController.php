<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionAttempt;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $levelDistribution = StudentProfile::selectRaw('level, count(*) as total')
            ->groupBy('level')
            ->orderBy('level')
            ->get();

        $topUsers = User::whereHas('studentProfile')
            ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->orderBy('student_profiles.xp', 'desc')
            ->select('users.name', 'student_profiles.xp', 'student_profiles.level')
            ->limit(10)
            ->get();

        $activeUsersWeek = StudentProfile::where('last_activity_date', '>=', Carbon::now()->subDays(7))->count();

        $practiceAttempts = QuestionAttempt::where('context', 'practice')->count();
        $practiceCorrect = QuestionAttempt::where('context', 'practice')->where('is_correct', true)->count();
        $practiceAccuracy = $practiceAttempts > 0 ? round(($practiceCorrect / $practiceAttempts) * 100) : null;

        return view('admin.analytics.index', compact(
            'levelDistribution',
            'topUsers',
            'activeUsersWeek',
            'practiceAttempts',
            'practiceAccuracy'
        ));
    }
}
