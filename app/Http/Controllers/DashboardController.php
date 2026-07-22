<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $studentProfile = $user->studentProfile;

        $recentAttempts = $studentProfile
            ? $user->questionAttempts()->with('question.topic')->latest()->limit(5)->get()
            : collect();

        $latestDiagnostic = $studentProfile
            ? $user->diagnosticResults()->latest('completed_at')->first()
            : null;

        return view('dashboard.index', compact('user', 'studentProfile', 'recentAttempts', 'latestDiagnostic'));
    }
}
