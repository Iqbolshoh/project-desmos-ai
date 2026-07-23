<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiTutorSession;
use App\Models\SavedGraph;
use App\Models\User;

class AiTutorSessionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('student')->get();

        foreach ($users as $user) {
            AiTutorSession::create([
                'user_id' => $user->id,
                'input_type' => 'text',
                'input_text' => 'Kvadrat tenglamani yeching: x^2 - 4 = 0',
                'ai_response' => [
                    'finalAnswer' => 'x = 2 yoki x = -2',
                    'explanation' => 'Tenglamani yechish uchun har ikki tomondan ildiz olinadi.',
                    'steps' => [
                        [
                            'stepNumber' => 1,
                            'title' => 'Tenglamani standart ko\'rinishga keltirish',
                            'explanation' => '4 ni o\'ng tomonga o\'tkazamiz.',
                            'mathExpression' => 'x^2 = 4'
                        ],
                        [
                            'stepNumber' => 2,
                            'title' => 'Ildiz olish',
                            'explanation' => 'Ikkala tomondan kvadrat ildiz olamiz.',
                            'mathExpression' => 'x = \pm 2'
                        ]
                    ]
                ],
                'desmos_state' => ['expression' => 'y=x^2-4'],
                'driver' => 'mock',
            ]);

            SavedGraph::create([
                'user_id' => $user->id,
                'title' => 'Parabola grafiki',
                'desmos_state' => ['expression' => 'y=x^2-4'],
            ]);
        }

        $this->command?->info('✓ AI Tutor Sessions and Saved Graphs seeded.');
    }
}
