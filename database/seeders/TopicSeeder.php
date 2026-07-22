<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

final class TopicSeeder extends Seeder
{
    private const TOPICS = [
        ['slug' => 'heart-of-algebra', 'name' => 'Heart of Algebra', 'domain' => 'algebra', 'icon' => 'sigma'],
        ['slug' => 'advanced-math', 'name' => 'Advanced Math', 'domain' => 'functions', 'icon' => 'square-radical'],
        ['slug' => 'problem-solving', 'name' => 'Problem Solving & Data Analysis', 'domain' => 'statistics', 'icon' => 'chart-bar'],
        ['slug' => 'geometry', 'name' => 'Geometry', 'domain' => 'geometry', 'icon' => 'shapes'],
        ['slug' => 'trigonometry', 'name' => 'Trigonometry', 'domain' => 'geometry', 'icon' => 'triangle'],
        ['slug' => 'functions', 'name' => 'Functions', 'domain' => 'functions', 'icon' => 'function-square'],
        ['slug' => 'statistics', 'name' => 'Statistics', 'domain' => 'statistics', 'icon' => 'chart-scatter'],
    ];

    public function run(): void
    {
        foreach (self::TOPICS as $index => $topic) {
            Topic::firstOrCreate(
                ['slug' => $topic['slug']],
                [...$topic, 'sort_order' => $index]
            );
        }

        $this->command?->info('✓ ' . count(self::TOPICS) . ' SAT topics seeded.');
    }
}
