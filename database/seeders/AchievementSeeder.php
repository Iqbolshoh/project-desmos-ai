<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'Ilk Qadam',
                'description' => 'Diagnostika testidan o\'tdingiz.',
                'icon' => 'target',
                'xp_reward' => 50,
            ],
            [
                'name' => 'Olovdek Issiq',
                'description' => '3 kunlik Streak (qatorasiga kirish).',
                'icon' => 'flame',
                'xp_reward' => 150,
            ],
            [
                'name' => 'Algebra Ustasi',
                'description' => 'Heart of Algebra yo\'nalishida 10 ta savol yechildi.',
                'icon' => 'sigma',
                'xp_reward' => 300,
            ],
            [
                'name' => 'Chempion',
                'description' => '1000 XP to\'pladingiz.',
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

        $this->command?->info('✓ Achievements seeded.');
    }
}
