<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticResult;
use App\Models\QuestionAttempt;
use App\Models\Topic;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $diagnosticsTaken = DiagnosticResult::count();
        $averageScore = (int) round(DiagnosticResult::avg('overall_score_estimate') ?? 0);

        $topicStats = Topic::orderBy('sort_order')->get()->map(function (Topic $topic) {
            $attempts = QuestionAttempt::whereHas('question', fn ($q) => $q->where('topic_id', $topic->id));
            $total = (clone $attempts)->count();
            $correct = (clone $attempts)->where('is_correct', true)->count();

            return [
                'topic' => $topic,
                'total' => $total,
                'correct' => $correct,
                'accuracy' => $total > 0 ? round(($correct / $total) * 100) : null,
            ];
        });

        return view('admin.reports.index', compact('totalUsers', 'diagnosticsTaken', 'averageScore', 'topicStats'));
    }
}
