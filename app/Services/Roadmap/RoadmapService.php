<?php

namespace App\Services\Roadmap;

use App\Models\Roadmap;
use App\Models\Topic;
use App\Models\User;

class RoadmapService
{
    /**
     * Generate a personalized SAT study roadmap for a user.
     */
    public function generateForUser(User $user): Roadmap
    {
        $profile = $user->studentProfile;
        $latestDiagnostic = $user->diagnosticResults()->latest('completed_at')->first();

        $currentScore = $latestDiagnostic
            ? $latestDiagnostic->overall_score_estimate
            : ($profile?->sat_current_score ?? 400);

        $targetScore = $profile?->sat_goal_score ?? 800;
        if ($targetScore <= $currentScore) {
            $targetScore = min(800, $currentScore + 100);
        }

        $dailyMinutes = $profile?->daily_goal_minutes ?? 30;

        $weakDomains = $this->rankWeakDomains($latestDiagnostic?->breakdown ?? []);
        $topicsByDomain = Topic::orderBy('sort_order')->get()->groupBy('domain');

        // Order topics: weakest domains first, then the remaining
        $orderedTopics = collect();
        foreach ($weakDomains as $domain) {
            $orderedTopics = $orderedTopics->merge($topicsByDomain->get($domain, collect()));
        }
        foreach ($topicsByDomain as $topics) {
            $orderedTopics = $orderedTopics->merge($topics->diff($orderedTopics));
        }
        $orderedTopics = $orderedTopics->unique('id')->values();
        if ($orderedTopics->isEmpty()) {
            $orderedTopics = collect([(object) ['name' => 'SAT Math']]);
        }

        $scoreGap = max($targetScore - $currentScore, 0);
        $pace = max($dailyMinutes / 30, 0.5);
        $estimatedWeeks = $scoreGap > 0
            ? (int) max(4, min(52, ceil($scoreGap / (10 * $pace))))
            : 4;

        $weeklyPlan = $this->buildWeeklyPlan($orderedTopics, $estimatedWeeks);
        $monthlyPlan = $this->buildMonthlyPlan($estimatedWeeks, $currentScore, $targetScore, $orderedTopics);

        Roadmap::where('user_id', $user->id)->where('status', 'active')->update(['status' => 'archived']);

        return Roadmap::create([
            'user_id' => $user->id,
            'current_score' => $currentScore,
            'goal_score' => $targetScore,
            'estimated_weeks' => $estimatedWeeks,
            'daily_study_minutes' => $dailyMinutes,
            'weekly_plan' => $weeklyPlan,
            'monthly_plan' => $monthlyPlan,
            'completion_percent' => 0,
            'status' => 'active',
            'generated_at' => now(),
        ]);
    }

    private function rankWeakDomains(array $breakdown): array
    {
        $ranked = [];
        foreach ($breakdown as $domain => $stats) {
            $total = $stats['total'] ?? 0;
            $ranked[$domain] = $total > 0 ? ($stats['correct'] / $total) * 100 : 50;
        }
        asort($ranked);

        return array_keys($ranked);
    }

    private function buildWeeklyPlan($orderedTopics, int $estimatedWeeks): array
    {
        $focusWeeks = min($estimatedWeeks, 6);
        $topics = $orderedTopics->values();

        $weeks = [];
        for ($week = 1; $week <= $focusWeeks; $week++) {
            $topic = $topics[($week - 1) % $topics->count()];
            $topicName = is_object($topic) && isset($topic->name) ? $topic->name : 'SAT Math';

            $weeks[] = [
                'week' => $week,
                'focus' => $topicName,
                'tasks' => [
                    ['id' => "w{$week}t1", 'title' => "{$topicName}: Theory and key formulas review", 'completed' => false],
                    ['id' => "w{$week}t2", 'title' => "{$topicName}: Targeted practice problem set", 'completed' => false],
                    ['id' => "w{$week}t3", 'title' => "{$topicName}: Review mistakes using Desmos AI Tutor", 'completed' => false],
                ],
            ];
        }

        if ($estimatedWeeks > $focusWeeks) {
            $lastWeek = $focusWeeks + 1;
            $weeks[] = [
                'week' => $lastWeek,
                'focus' => 'Exam Readiness & Full Mock Prep',
                'tasks' => [
                    ['id' => "w{$lastWeek}t1", 'title' => 'Retake diagnostic test to benchmark progress', 'completed' => false],
                    ['id' => "w{$lastWeek}t2", 'title' => 'Practice time management and Desmos speed tricks', 'completed' => false],
                    ['id' => "w{$lastWeek}t3", 'title' => "Rotational review across all topics for {$estimatedWeeks} weeks", 'completed' => false],
                ],
            ];
        }

        return $weeks;
    }

    private function buildMonthlyPlan(int $estimatedWeeks, int $currentScore, int $targetScore, $orderedTopics): array
    {
        $months = max(1, (int) ceil($estimatedWeeks / 4));
        $topics = $orderedTopics->values();
        $gap = $targetScore - $currentScore;

        $plan = [];
        for ($month = 1; $month <= $months; $month++) {
            $milestoneScore = (int) round($currentScore + ($gap * $month / $months));
            $topic = $topics[($month - 1) % $topics->count()];
            $topicName = is_object($topic) && isset($topic->name) ? $topic->name : 'SAT Math';

            $plan[] = [
                'month' => $month,
                'goal' => $month === $months
                    ? 'Final comprehensive review and full mock practice tests'
                    : "Deep dive into {$topicName} and core skills",
                'target_score' => min(800, $milestoneScore),
            ];
        }

        return $plan;
    }
}
