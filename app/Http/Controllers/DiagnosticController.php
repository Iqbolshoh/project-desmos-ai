<?php

namespace App\Http\Controllers;

use App\Models\DiagnosticResult;
use App\Services\Diagnostic\DiagnosticService;
use App\Services\Gamification\AchievementService;
use App\Services\Gamification\GamificationService;
use App\Services\Roadmap\RoadmapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiagnosticController extends Controller
{
    protected DiagnosticService $diagnosticService;
    protected RoadmapService $roadmapService;
    protected GamificationService $gamificationService;
    protected AchievementService $achievementService;

    public function __construct(
        DiagnosticService $diagnosticService,
        RoadmapService $roadmapService,
        GamificationService $gamificationService,
        AchievementService $achievementService
    ) {
        $this->diagnosticService = $diagnosticService;
        $this->roadmapService = $roadmapService;
        $this->gamificationService = $gamificationService;
        $this->achievementService = $achievementService;
    }

    /**
     * Display diagnostic test start introduction.
     */
    public function start(): View
    {
        return view('diagnostic.start');
    }

    /**
     * Render the diagnostic test questions.
     */
    public function show(): View|RedirectResponse
    {
        $questions = $this->diagnosticService->generateTest();
        if ($questions->isEmpty()) {
            return redirect()
                ->route('dashboard.index')
                ->with('error', 'No diagnostic test questions found. Please contact the administrator.');
        }

        return view('diagnostic.show', compact('questions'));
    }

    /**
     * Submit diagnostic test answers and generate results.
     */
    public function submit(Request $request): RedirectResponse
    {
        $answers = $request->input('answers', []);
        $user = auth()->user();

        $result = $this->diagnosticService->processResults($user, $answers);

        // Award Gamification XP & Streak
        $this->gamificationService->addXp($user, (int) config('gamification.xp.diagnostic_complete', 50), 'Diagnostic Completed');
        $this->gamificationService->updateStreak($user);

        // Generate Personalized Roadmap
        $this->roadmapService->generateForUser($user);

        $this->achievementService->checkAndAward($user);

        return redirect()->route('diagnostic.results', $result->id);
    }

    /**
     * Display diagnostic test result analysis.
     */
    public function results(DiagnosticResult $result): View
    {
        if ($result->user_id !== auth()->id()) {
            abort(403);
        }

        return view('diagnostic.results', compact('result'));
    }
}
