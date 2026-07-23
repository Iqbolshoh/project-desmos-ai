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
        if (!$profile) return;

        $today = Carbon::today();
        $lastActive = $profile->last_active_at ? Carbon::parse($profile->last_active_at)->startOfDay() : null;

        if (!$lastActive) {
            $profile->streak = 1;
        } elseif ($lastActive->isYesterday()) {
            $profile->streak += 1;
        } elseif (!$lastActive->isToday()) {
            $profile->streak = 1; // Streak lost
        }

        $profile->last_active_at = now();
        $profile->save();
        
        // Return streak bonus if they practiced today for the first time
        if (!$lastActive || !$lastActive->isToday()) {
            $bonus = min($profile->streak * config('gamification.xp.streak_bonus'), config('gamification.xp.streak_bonus_cap'));
            $this->addXp($user, $bonus, 'Streak bonus');
            return $bonus;
        }
        
        return 0;
    }
}
