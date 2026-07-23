<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StudentProfile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('B7654321');

        for ($i = 1; $i <= 20; $i++) {
            $user = User::withoutGlobalScopes()->create([
                'name' => "Talaba $i",
                'email' => "talaba$i@desmosai.test",
                'email_verified_at' => now(),
                'password' => $password,
                'remember_token' => Str::random(10),
            ]);

            $user->syncRoles(['student']);

            // Randomize scores for variety
            $currentScore = rand(400, 750);
            $goalScore = min(800, $currentScore + rand(50, 150));
            $xp = rand(100, 5000);
            $streak = rand(0, 30);
            $level = floor($xp / 1000) + 1;

            StudentProfile::create([
                'user_id' => $user->id,
                'sat_current_score' => $currentScore,
                'sat_goal_score' => $goalScore,
                'xp' => $xp,
                'level' => $level,
                'streak_current' => $streak,
                'streak_longest' => $streak + rand(0, 5),
                'last_activity_date' => rand(0, 1) ? now()->subDays(rand(1, 5))->toDateString() : now()->toDateString(),
            ]);
        }

        $this->command?->info('✓ 20 ta talaba va ularning profillari yaratildi.');
    }
}
