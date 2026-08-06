<?php

namespace App\Services\Diagnostic;

use App\Models\DiagnosticResult;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DiagnosticService
{
    /**
     * Generate diagnostic test questions set.
     */
    public function generateTest(): Collection
    {
        return Question::with('topic')
            ->where('is_diagnostic', true)
            ->inRandomOrder()
            ->limit(20)
            ->get();
    }

    /**
     * Process diagnostic submission, calculate estimated score and weakness breakdown.
     */
    public function processResults(User $user, array $answers): DiagnosticResult
    {
        $questions = Question::with('topic')
            ->whereIn('id', array_keys($answers))
            ->get()
            ->keyBy('id');

        $correctCount = 0;
        $totalQuestions = count($answers);
        $domainScores = [
            'algebra' => ['correct' => 0, 'total' => 0],
            'geometry' => ['correct' => 0, 'total' => 0],
            'functions' => ['correct' => 0, 'total' => 0],
            'statistics' => ['correct' => 0, 'total' => 0],
        ];

        foreach ($answers as $qId => $userAnswer) {
            $question = $questions->get($qId);
            if (!$question) {
                continue;
            }

            $domain = $question->topic->domain ?? 'algebra';
            if (!isset($domainScores[$domain])) {
                $domainScores[$domain] = ['correct' => 0, 'total' => 0];
            }

            $domainScores[$domain]['total']++;

            if (strtolower(trim((string) $question->correct_answer)) === strtolower(trim((string) $userAnswer))) {
                $correctCount++;
                $domainScores[$domain]['correct']++;
            }
        }

        // Estimate score (Base 400 + (correct/total) * 400)
        $scoreEstimate = 400 + ($totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 400) : 0);

        // Identify primary area of weakness
        $weakness = 'algebra';
        $minPercent = 100;
        foreach ($domainScores as $dom => $stats) {
            if ($stats['total'] > 0) {
                $percent = ($stats['correct'] / $stats['total']) * 100;
                if ($percent < $minPercent) {
                    $minPercent = $percent;
                    $weakness = $dom;
                }
            }
        }

        $result = DiagnosticResult::create([
            'user_id' => $user->id,
            'total_questions' => $totalQuestions,
            'correct_count' => $correctCount,
            'overall_score_estimate' => $scoreEstimate,
            'breakdown' => $domainScores,
            'weakness_summary' => 'Your primary area for improvement is ' . ucfirst($weakness) . '. Dedicated practice in this domain is recommended.',
            'completed_at' => now(),
        ]);

        // Update Student Profile current score
        $profile = $user->studentProfile;
        if ($profile) {
            $profile->sat_current_score = $scoreEstimate;
            $profile->sat_goal_score = max($profile->sat_goal_score ?? 0, min(800, $scoreEstimate + 100));
            $profile->save();
        }

        return $result;
    }
}
