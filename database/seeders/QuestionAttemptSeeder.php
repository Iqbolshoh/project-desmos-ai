<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionAttempt;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionAttemptSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('student')->get();
        $questions = Question::where('is_diagnostic', false)->get();

        if ($questions->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            $attemptCount = rand(5, 15);
            $userQuestions = $questions->random(min($attemptCount, $questions->count()));

            foreach ($userQuestions as $q) {
                $isCorrect = rand(0, 1) == 1;
                QuestionAttempt::create([
                    'user_id' => $user->id,
                    'question_id' => $q->id,
                    'context' => 'practice',
                    'selected_answer' => $isCorrect ? $q->correct_answer : 'X',
                    'is_correct' => $isCorrect,
                    'time_spent_seconds' => rand(15, 120),
                    'xp_earned' => $isCorrect ? 10 : 0,
                ]);
            }
        }

        $this->command?->info('✓ Question attempts seeded.');
    }
}
