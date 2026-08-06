<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            TopicSeeder::class,
            QuestionSeeder::class,
            PracticeQuestionSeeder::class,
            AchievementSeeder::class,

            DiagnosticResultSeeder::class,
            QuestionAttemptSeeder::class,
            AiTutorSessionSeeder::class,
            ChatThreadSeeder::class,
            RoadmapSeeder::class,
            UserAchievementSeeder::class,
        ]);
    }
}
