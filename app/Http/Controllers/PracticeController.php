<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Question;
use App\Models\QuestionAttempt;
use App\Services\Gamification\GamificationService;

class PracticeController extends Controller
{
    protected GamificationService $gamification;

    public function __construct(GamificationService $gamification)
    {
        $this->gamification = $gamification;
    }

    public function index()
    {
        $topics = Topic::orderBy('sort_order')->get();
        return view('practice.index', compact('topics'));
    }

    public function topic(Topic $topic)
    {
        $stats = [
            'easy' => Question::where('topic_id', $topic->id)->where('difficulty', 'easy')->count(),
            'medium' => Question::where('topic_id', $topic->id)->where('difficulty', 'medium')->count(),
            'hard' => Question::where('topic_id', $topic->id)->where('difficulty', 'hard')->count(),
        ];
        
        return view('practice.topic', compact('topic', 'stats'));
    }

    public function quiz(Request $request, Topic $topic)
    {
        $difficulty = $request->query('difficulty', 'medium');
        
        // Find a random question the user hasn't correctly answered recently, 
        // or just a random question for simplicity in MVP.
        $question = Question::where('topic_id', $topic->id)
            ->where('difficulty', $difficulty)
            ->inRandomOrder()
            ->first();

        if (!$question) {
            // Fallback to any difficulty if chosen one has no questions
            $question = Question::where('topic_id', $topic->id)->inRandomOrder()->first();
        }

        if (!$question) {
            return redirect()->route('practice.topic', $topic->slug)->with('error', 'Hozircha bu mavzuda savollar mavjud emas.');
        }

        return view('practice.quiz', compact('topic', 'question', 'difficulty'));
    }

    public function submit(Request $request, Question $question)
    {
        $request->validate([
            'answer' => 'required|string'
        ]);

        $userAnswer = $request->input('answer');
        $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($question->correct_answer));

        QuestionAttempt::create([
            'user_id' => auth()->id(),
            'question_id' => $question->id,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'time_spent_seconds' => $request->input('time_spent', 0),
        ]);

        if ($isCorrect) {
            $xpAmount = config("gamification.xp.practice_correct_{$question->difficulty}", 10);
            $this->gamification->addXp(auth()->user(), $xpAmount, 'Correct Answer');
            $this->gamification->updateStreak(auth()->user());
        }

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
            'desmos_expressions' => $question->desmos_expressions
        ]);
    }
}
