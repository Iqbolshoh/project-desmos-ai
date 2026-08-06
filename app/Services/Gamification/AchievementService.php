<?php

namespace App\Services\Gamification;

use App\Models\Achievement;
use App\Models\QuestionAttempt;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Collection;

class AchievementService
{
    public function __construct(private GamificationService $gamification)
    {
    }

    /**
     * Check candidate achievements for a user and award newly unlocked badges.
     *
     * @return Collection<int, Achievement>
     */
    public function checkAndAward(User $user): Collection
    {
        $profile = $user->studentProfile;
        if (!$profile) {
            return collect();
        }

        $earnedAchievementIds = UserAchievement::where('user_id', $user->id)->pluck('achievement_id');
        $candidates = Achievement::whereNotIn('id', $earnedAchievementIds)->get();

        $newlyAwarded = collect();

        foreach ($candidates as $achievement) {
            if ($this->isEarned($achievement->slug, $user, $profile)) {
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'earned_at' => now(),
                ]);

                if ($achievement->xp_reward > 0) {
                    $this->gamification->addXp($user, $achievement->xp_reward, "Achievement: {$achievement->name}");
                }

                $newlyAwarded->push($achievement);
            }
        }

        return $newlyAwarded;
    }

    private function isEarned(string $slug, User $user, $profile): bool
    {
        return match ($slug) {
            'first-step', 'ilk-qadam' => $user->diagnosticResults()->exists(),
            'on-fire', 'olovdek-issiq' => $profile->streak_current >= 3,
            'algebra-master', 'algebra-ustasi' => $this->correctPracticeCount($user, 'heart-of-algebra') >= 10,
            'champion', 'chempion' => $profile->xp >= 1000,
            default => false,
        };
    }

    private function correctPracticeCount(User $user, string $topicSlug): int
    {
        $topicId = Topic::where('slug', $topicSlug)->value('id');
        if (!$topicId) {
            return 0;
        }

        return QuestionAttempt::where('user_id', $user->id)
            ->where('is_correct', true)
            ->whereHas('question', fn($q) => $q->where('topic_id', $topicId))
            ->count();
    }
}
