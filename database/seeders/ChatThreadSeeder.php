<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\ChatThread;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatThreadSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('student')->get();

        foreach ($users as $user) {
            $thread = ChatThread::create([
                'user_id' => $user->id,
                'title' => 'Trigonometriya haqida suhbat',
            ]);

            ChatMessage::create([
                'chat_thread_id' => $thread->id,
                'role' => 'user',
                'message' => 'Sinus va kosinus farqini tushuntirib bera olasizmi?',
            ]);

            ChatMessage::create([
                'chat_thread_id' => $thread->id,
                'role' => 'assistant',
                'message' => 'Albatta! To\'g\'ri burchakli uchburchakda sinus - qarama-qarshi katetning gipotenuzaga nisbati. Kosinus esa yopishgan katetning gipotenuzaga nisbati hisoblanadi.',
            ]);
        }

        $this->command?->info('✓ Chat threads and messages seeded.');
    }
}
