<?php

namespace App\Services\Gamification;

use App\Models\User;
use Illuminate\Support\Carbon;

class GamificationService
{
    /**
     * Award XP to user and update student level.
     */
    public function addXp(User $user, int $amount, string $reason = ''): void
    {
        $profile = $user->studentProfile;
        if (!$profile) {
            return;
        }

        $profile->xp += $amount;

        // Calculate Level based on XP requirements
        $levels = config('gamification.levels', [1 => 0, 2 => 100, 3 => 300, 4 => 600, 5 => 1000]);
        $newLevel = 1;
        foreach ($levels as $level => $reqXp) {
            if ($profile->xp >= $reqXp) {
                $newLevel = $level;
            }
        }

        $profile->level = $newLevel;
        $profile->save();
    }

    /**
     * Update user activity streak and award daily streak bonus.
     */
    public function updateStreak(User $user): int
    {
        $profile = $user->studentProfile;
        if (!$profile) {
            return 0;
        }

        $today = Carbon::today();
        $lastActive = $profile->last_activity_date ? Carbon::parse($profile->last_activity_date)->startOfDay() : null;

        $wasFirstActivityToday = !$lastActive || !$lastActive->isToday();

        if (!$lastActive) {
            $profile->streak_current = 1;
        } elseif ($lastActive->isYesterday()) {
            $profile->streak_current += 1;
        } elseif (!$lastActive->isToday()) {
            $profile->streak_current = 1;
        }

        if ($profile->streak_current > $profile->streak_longest) {
            $profile->streak_longest = $profile->streak_current;
        }

        $profile->last_activity_date = $today;
        $profile->save();

        if ($wasFirstActivityToday) {
            $baseBonus = (int) config('gamification.xp.streak_bonus', 10);
            $capBonus = (int) config('gamification.xp.streak_bonus_cap', 50);
            $bonus = min($profile->streak_current * $baseBonus, $capBonus);

            $this->addXp($user, $bonus, 'Daily streak bonus');

            return $bonus;
        }

        return 0;
    }
}
