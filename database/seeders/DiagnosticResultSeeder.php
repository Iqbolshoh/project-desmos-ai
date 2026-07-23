<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiagnosticResult;
use App\Models\User;

class DiagnosticResultSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('student')->get();

        foreach ($users as $user) {
            // Some users might have taken it 2 times, some 1.
            $attempts = rand(1, 2);

            for ($i = 0; $i < $attempts; $i++) {
                $scoreEstimate = rand(400, $user->studentProfile->sat_current_score ?? 600);
                
                DiagnosticResult::create([
                    'user_id' => $user->id,
                    'total_questions' => 20,
                    'correct_count' => rand(5, 18),
                    'overall_score_estimate' => $scoreEstimate,
                    'breakdown' => [
                        'algebra' => ['correct' => rand(1, 5), 'total' => 5],
                        'geometry' => ['correct' => rand(1, 5), 'total' => 5],
                        'functions' => ['correct' => rand(1, 5), 'total' => 5],
                        'statistics' => ['correct' => rand(1, 5), 'total' => 5],
                    ],
                    'weakness_summary' => "Sizning eng zaif nuqtangiz - Geometry. Shu yo'nalishda ko'proq mashq qilishingiz tavsiya etiladi.",
                    'completed_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        $this->command?->info('✓ Diagnostic results seeded.');
    }
}
