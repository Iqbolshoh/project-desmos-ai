<?php

namespace App\Services\Diagnostic;

use App\Models\User;
use App\Models\Question;
use App\Models\DiagnosticResult;

class DiagnosticService
{
    public function generateTest()
    {
        // Fetch diagnostic questions. For a real app, you might want 5 per domain.
        // Currently we fetch all diagnostic questions seeded.
        return Question::where('is_diagnostic', true)
            ->inRandomOrder()
            ->limit(20)
            ->get();
    }

    public function processResults(User $user, array $answers)
    {
        $questions = Question::whereIn('id', array_keys($answers))->get()->keyBy('id');
        
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
            if (!$question) continue;

            $domain = $question->topic->domain ?? 'algebra';
            if (!isset($domainScores[$domain])) {
                $domainScores[$domain] = ['correct' => 0, 'total' => 0];
            }
            
            $domainScores[$domain]['total']++;

            if (strtolower(trim($question->correct_answer)) === strtolower(trim($userAnswer))) {
                $correctCount++;
                $domainScores[$domain]['correct']++;
            }
        }

        // Estimate score (very naive mapping 20 questions -> 800 scale)
        // Base 400 + (correct/total) * 400
        $scoreEstimate = 400 + ($totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 400) : 0);

        // Find weakest domain
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
            'weakness_summary' => "Sizning eng zaif nuqtangiz - " . ucfirst($weakness) . ". Shu yo'nalishda ko'proq mashq qilishingiz tavsiya etiladi.",
            'completed_at' => now(),
        ]);

        // Update Student Profile
        $profile = $user->studentProfile;
        if ($profile) {
            $profile->sat_current_score = $scoreEstimate;
            $profile->sat_goal_score = max($profile->sat_goal_score ?? 0, min(800, $scoreEstimate + 100));
            $profile->save();
        }

        return $result;
    }
}
