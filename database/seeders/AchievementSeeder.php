<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'First Step',
                'description' => 'Completed the diagnostic placement test.',
                'icon' => 'target',
                'xp_reward' => 50,
            ],
            [
                'name' => 'On Fire',
                'description' => 'Maintained a 3-day activity streak.',
                'icon' => 'flame',
                'xp_reward' => 150,
            ],
            [
                'name' => 'Algebra Master',
                'description' => 'Solved 10 questions in Heart of Algebra.',
                'icon' => 'sigma',
                'xp_reward' => 300,
            ],
            [
                'name' => 'Champion',
                'description' => 'Earned a total of 1,000 XP.',
                'icon' => 'award',
                'xp_reward' => 1000,
            ],
        ];

        foreach ($achievements as $ach) {
            $ach['slug'] = Str::slug($ach['name']);
            Achievement::firstOrCreate(
                ['slug' => $ach['slug']],
                $ach
            );
        }

        $this->command?->info('✓ Achievements seeded in English.');
    }
}
