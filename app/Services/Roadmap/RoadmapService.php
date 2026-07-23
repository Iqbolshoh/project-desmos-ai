<?php

namespace App\Services\Roadmap;

use App\Models\User;
use App\Models\Roadmap;

class RoadmapService
{
    public function generateForUser(User $user)
    {
        $profile = $user->studentProfile;
        $latestDiagnostic = $user->diagnosticResults()->latest()->first();

        $currentScore = $latestDiagnostic ? $latestDiagnostic->overall_score_estimate : 400;
        $targetScore = $profile->target_score ?? 800;
        
        $weakness = $latestDiagnostic ? ($latestDiagnostic->weakness_summary ?? 'Algebra') : 'Barcha mavzular';

        // Delete existing uncompleted roadmaps or just create a new one as active
        Roadmap::where('user_id', $user->id)->update(['is_active' => false]);

        $roadmap = Roadmap::create([
            'user_id' => $user->id,
            'title' => "Maksimal Natija: $targetScore",
            'description' => "Joriy ball: $currentScore. Asosiy e'tibor qaratilishi kerak: $weakness",
            'is_active' => true,
            'progress_percent' => 0,
            'stages' => [
                [
                    'id' => 1,
                    'title' => 'Asoslarni mustahkamlash',
                    'tasks' => [
                        ['id' => 't1', 'title' => 'Heart of Algebra qoidalarini takrorlash', 'completed' => false],
                        ['id' => 't2', 'title' => '10 ta oson algebra masalasini yechish', 'completed' => false],
                    ],
                    'completed' => false
                ],
                [
                    'id' => 2,
                    'title' => 'Zaifliklar ustida ishlash',
                    'tasks' => [
                        ['id' => 't3', 'title' => 'Geometriya qoidalarini yodlash', 'completed' => false],
                        ['id' => 't4', 'title' => '15 ta o\'rtacha masalani yechish', 'completed' => false],
                    ],
                    'completed' => false
                ],
                [
                    'id' => 3,
                    'title' => 'Imtihonga tayyorgarlik',
                    'tasks' => [
                        ['id' => 't5', 'title' => 'To\'liq Mock Test ishlash', 'completed' => false],
                        ['id' => 't6', 'title' => 'Vaqtni to\'g\'ri taqsimlashni mashq qilish', 'completed' => false],
                    ],
                    'completed' => false
                ]
            ]
        ]);

        return $roadmap;
    }
}
