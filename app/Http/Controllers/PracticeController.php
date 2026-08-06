<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionAttempt;
use App\Models\Topic;
use App\Services\Gamification\AchievementService;
use App\Services\Gamification\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PracticeController extends Controller
{
    protected GamificationService $gamification;
    protected AchievementService $achievements;

    public function __construct(GamificationService $gamification, AchievementService $achievements)
    {
        $this->gamification = $gamification;
        $this->achievements = $achievements;
    }

    /**
     * Display all practice topics.
     */
    public function index(): View
    {
        $topics = Topic::orderBy('sort_order')->get();

        return view('practice.index', compact('topics'));
    }

    /**
     * Display topic details and question counts.
     */
    public function topic(Topic $topic): View
    {
        $stats = [
            'easy' => Question::where('topic_id', $topic->id)->where('difficulty', 'easy')->count(),
            'medium' => Question::where('topic_id', $topic->id)->where('difficulty', 'medium')->count(),
            'hard' => Question::where('topic_id', $topic->id)->where('difficulty', 'hard')->count(),
        ];

        return view('practice.topic', compact('topic', 'stats'));
    }

    /**
     * Render quiz view for a selected topic and difficulty.
     */
    public function quiz(Request $request, Topic $topic): View|RedirectResponse
    {
        $difficulty = $request->query('difficulty', 'medium');

        $question = Question::with('topic')
            ->where('topic_id', $topic->id)
            ->where('difficulty', $difficulty)
            ->inRandomOrder()
            ->first();

        if (!$question) {
            $question = Question::with('topic')
                ->where('topic_id', $topic->id)
                ->inRandomOrder()
                ->first();
        }

        if (!$question) {
            return redirect()
                ->route('practice.topic', $topic->slug)
                ->with('error', 'No practice questions are available for this topic yet.');
        }

        return view('practice.quiz', compact('topic', 'question', 'difficulty'));
    }

    /**
     * Process quiz answer submission and award XP.
     */
    public function submit(Request $request, Question $question): JsonResponse
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $userAnswer = $request->input('answer');
        $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($question->correct_answer));
        $xpAmount = $isCorrect ? config("gamification.xp.practice_correct_{$question->difficulty}", 10) : 0;

        QuestionAttempt::create([
            'user_id' => auth()->id(),
            'question_id' => $question->id,
            'context' => 'practice',
            'selected_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'time_spent_seconds' => (int) $request->input('time_spent', 0),
            'xp_earned' => $xpAmount,
        ]);

        if ($isCorrect) {
            $user = auth()->user();
            if ($user) {
                $this->gamification->addXp($user, $xpAmount, 'Correct Answer');
                $this->gamification->updateStreak($user);
                $this->achievements->checkAndAward($user);
            }
        }

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
            'desmos_expressions' => $question->desmos_expressions,
        ]);
    }
}
