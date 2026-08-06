<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoadmapSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('student')->get();

        foreach ($users as $user) {
            $currentScore = $user->studentProfile->sat_current_score ?? 500;
            $goalScore    = $user->studentProfile->sat_goal_score ?? 800;

            Roadmap::create([
                'user_id'             => $user->id,
                'current_score'       => $currentScore,
                'goal_score'          => $goalScore,
                'estimated_weeks'     => rand(4, 12),
                'daily_study_minutes' => rand(30, 90),
                'weekly_plan'         => [
                    ['week' => 1, 'topic' => 'Algebra Fundamentals Review', 'completed' => true],
                    ['week' => 2, 'topic' => 'Linear Equations & Systems Practice', 'completed' => rand(0, 1) === 1],
                    ['week' => 3, 'topic' => 'Geometry & Special Right Triangles', 'completed' => false],
                    ['week' => 4, 'topic' => 'Full Length SAT Practice Test 1', 'completed' => false],
                ],
                'completion_percent'  => rand(10, 50),
                'status'              => 'active',
                'generated_at'        => now()->subDays(rand(1, 10)),
            ]);
        }

        $this->command?->info('✓ Roadmaps seeded in English.');
    }
}
