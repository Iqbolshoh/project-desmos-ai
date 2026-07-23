<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Diagnostic\DiagnosticService;
use App\Services\Roadmap\RoadmapService;
use App\Services\Gamification\GamificationService;
use App\Services\Gamification\AchievementService;
use App\Models\DiagnosticResult;

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

    public function start()
    {
        return view('diagnostic.start');
    }

    public function show()
    {
        $questions = $this->diagnosticService->generateTest();
        if ($questions->isEmpty()) {
            return redirect()->route('dashboard.index')->with('error', 'Diagnostik test savollari topilmadi. Adminstrator bilan bog\'laning.');
        }

        return view('diagnostic.show', compact('questions'));
    }

    public function submit(Request $request)
    {
        $answers = $request->input('answers', []);
        
        $result = $this->diagnosticService->processResults(auth()->user(), $answers);
        
        // Gamification
        $this->gamificationService->addXp(auth()->user(), config('gamification.xp.diagnostic_complete'), 'Diagnostic Completed');
        $this->gamificationService->updateStreak(auth()->user());

        // Generate Roadmap based on results
        $this->roadmapService->generateForUser(auth()->user());

        $this->achievementService->checkAndAward(auth()->user());

        return redirect()->route('diagnostic.results', $result->id);
    }

    public function results(DiagnosticResult $result)
    {
        if ($result->user_id !== auth()->id()) {
            abort(403);
        }

        return view('diagnostic.results', compact('result'));
    }
}
