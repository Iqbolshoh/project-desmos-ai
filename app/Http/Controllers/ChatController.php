<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use App\Services\AiTutor\Contracts\AiTutorServiceInterface;
use App\Services\AiTutor\DTO\ChatRequestDTO;

class ChatController extends Controller
{
    protected AiTutorServiceInterface $aiTutor;

    public function __construct(AiTutorServiceInterface $aiTutor)
    {
        $this->aiTutor = $aiTutor;
    }

    public function index()
    {
        // Get or create the latest thread for the user
        $thread = ChatThread::firstOrCreate(
            ['user_id' => auth()->id()],
            ['title' => 'Yangi suhbat']
        );

        $messages = $thread->messages()->orderBy('created_at')->get();

        return view('chat.index', compact('thread', 'messages'));
    }

    public function send(Request $request, ChatThread $thread)
    {
        if ($thread->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessageText = $request->input('message');

        // Save User Message
        ChatMessage::create([
            'chat_thread_id' => $thread->id,
            'role' => 'user',
            'message' => $userMessageText
        ]);

        // Get history for context
        $history = $thread->messages()->orderBy('created_at')->limit(10)->get()->map(function($msg) {
            return ['role' => $msg->role, 'content' => $msg->message];
        })->toArray();

        // Get AI Reply
        $replyText = $this->aiTutor->chatReply(new ChatRequestDTO($userMessageText, $thread->id, $history));

        // Save AI Message
        $aiMsg = ChatMessage::create([
            'chat_thread_id' => $thread->id,
            'role' => 'assistant',
            'message' => $replyText
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'reply' => $aiMsg->message
            ]);
        }

        return redirect()->route('chat.index');
    }
}
