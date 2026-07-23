<?php

namespace App\Services\Roadmap;

use App\Models\Roadmap;
use App\Models\Topic;
use App\Models\User;

class RoadmapService
{
    public function generateForUser(User $user): Roadmap
    {
        $profile = $user->studentProfile;
        $latestDiagnostic = $user->diagnosticResults()->latest('completed_at')->first();

        $currentScore = $latestDiagnostic
            ? $latestDiagnostic->overall_score_estimate
            : ($profile->sat_current_score ?? 400);

        $targetScore = $profile?->sat_goal_score ?? 800;
        if ($targetScore <= $currentScore) {
            $targetScore = min(800, $currentScore + 100);
        }

        $dailyMinutes = $profile?->daily_goal_minutes ?? 30;

        $weakDomains = $this->rankWeakDomains($latestDiagnostic?->breakdown ?? []);
        $topicsByDomain = Topic::orderBy('sort_order')->get()->groupBy('domain');

        // Order topics: weakest domains first, then the rest.
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
        // Show a concrete plan for the first stretch of weeks; the rest of the
        // program repeats the same rotation until the exam-readiness weeks.
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
                    ['id' => "w{$week}t1", 'title' => "{$topicName}: nazariya va formulalarni takrorlash", 'completed' => false],
                    ['id' => "w{$week}t2", 'title' => "{$topicName} bo'yicha mashqlar (Practice) yechish", 'completed' => false],
                    ['id' => "w{$week}t3", 'title' => "{$topicName}: xato qilingan savollarni AI Tutor bilan qayta ko'rib chiqish", 'completed' => false],
                ],
            ];
        }

        if ($estimatedWeeks > $focusWeeks) {
            $lastWeek = $focusWeeks + 1;
            $weeks[] = [
                'week' => $lastWeek,
                'focus' => 'Imtihonga tayyorgarlik',
                'tasks' => [
                    ['id' => "w{$lastWeek}t1", 'title' => "To'liq diagnostika testini qayta yechish", 'completed' => false],
                    ['id' => "w{$lastWeek}t2", 'title' => "Vaqtni to'g'ri taqsimlashni mashq qilish", 'completed' => false],
                    ['id' => "w{$lastWeek}t3", 'title' => "Qolgan {$estimatedWeeks} haftalik davrda barcha mavzularni aylanma tartibda takrorlash", 'completed' => false],
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
                    ? "Yakuniy takrorlash va to'liq mock testlar"
                    : "{$topicName} va bog'liq mavzularni chuqur o'zlashtirish",
                'target_score' => min(800, $milestoneScore),
            ];
        }

        return $plan;
    }
}
