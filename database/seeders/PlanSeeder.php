<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

final class PlanSeeder extends Seeder
{
    /**
     * Seed the two subscription tiers. Prices and limits are only defaults —
     * admins tune them later from the Plans panel.
     */
    public function run(): void
    {
        Plan::updateOrCreate(
            ['slug' => Plan::DEFAULT_SLUG],
            [
                'name' => 'Free',
                'tagline' => 'Start your SAT Math prep at no cost',
                'price' => 0,
                'currency' => 'USD',
                'period' => 'forever',
                'ai_enabled' => true,
                'ai_daily_limit' => 3,
                'features' => [
                    'Diagnostic placement test',
                    '3 AI tutor requests daily',
                    'Practice question bank',
                    'Student dashboard & leaderboard',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium',
                'tagline' => 'Unlimited AI tutoring for serious prep',
                'price' => 19,
                'currency' => 'USD',
                'period' => 'month',
                'ai_enabled' => true,
                'ai_daily_limit' => null,
                'features' => [
                    'Unlimited AI tutor solutions',
                    '24/7 AI Chat Tutor',
                    'Personalized study roadmap',
                    'Saved graphs & session history',
                    'Gamification & achievement badges',
                ],
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ]
        );

        $this->command?->info('✓ Plans seeded: Free ($0, 3 AI/day) and Premium ($19/mo, unlimited AI).');
    }
}
