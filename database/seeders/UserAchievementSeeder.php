<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAchievement;
use App\Models\Achievement;
use App\Models\User;

class UserAchievementSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('student')->get();
        $achievements = Achievement::all();

        if ($achievements->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            $count = rand(1, 4);
            $randomAchievements = $achievements->random($count);

            foreach ($randomAchievements as $achievement) {
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'earned_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        $this->command?->info('✓ User achievements seeded.');
    }
}
