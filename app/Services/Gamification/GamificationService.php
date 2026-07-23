<?php

namespace App\Services\Gamification;

use App\Models\User;
use Carbon\Carbon;

class GamificationService
{
    public function addXp(User $user, int $amount, string $reason = '')
    {
        $profile = $user->studentProfile;
        if (!$profile) return;

        $profile->xp += $amount;
        
        // Calculate Level
        $levels = config('gamification.levels');
        $newLevel = 1;
        foreach ($levels as $level => $reqXp) {
            if ($profile->xp >= $reqXp) {
                $newLevel = $level;
            }
        }
        
        $profile->level = $newLevel;
        $profile->save();
    }
    
    public function updateStreak(User $user)
    {
        $profile = $user->studentProfile;
        if (!$profile) return 0;

        $today = Carbon::today();
        $lastActive = $profile->last_activity_date ? Carbon::parse($profile->last_activity_date)->startOfDay() : null;

        $wasFirstActivityToday = !$lastActive || !$lastActive->isToday();

        if (!$lastActive) {
            $profile->streak_current = 1;
        } elseif ($lastActive->isYesterday()) {
            $profile->streak_current += 1;
        } elseif (!$lastActive->isToday()) {
            $profile->streak_current = 1; // Streak lost
        }

        if ($profile->streak_current > $profile->streak_longest) {
            $profile->streak_longest = $profile->streak_current;
        }

        $profile->last_activity_date = $today;
        $profile->save();

        // Return streak bonus if they practiced today for the first time
        if ($wasFirstActivityToday) {
            $bonus = min($profile->streak_current * config('gamification.xp.streak_bonus'), config('gamification.xp.streak_bonus_cap'));
            $this->addXp($user, $bonus, 'Streak bonus');
            return $bonus;
        }

        return 0;
    }
}
