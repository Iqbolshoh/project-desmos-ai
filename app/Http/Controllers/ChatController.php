<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatThread;
use App\Services\AiTutor\Contracts\AiTutorServiceInterface;
use App\Services\AiTutor\DTO\ChatRequestDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    protected AiTutorServiceInterface $aiTutor;

    public function __construct(AiTutorServiceInterface $aiTutor)
    {
        $this->aiTutor = $aiTutor;
    }

    /**
     * Display current chat thread messages.
     */
    public function index(): View
    {
        $thread = ChatThread::firstOrCreate(
            ['user_id' => auth()->id()],
            ['title' => 'New Chat Session']
        );

        $messages = $thread->messages()->orderBy('created_at')->get();

        return view('chat.index', compact('thread', 'messages'));
    }

    /**
     * Send user message to AI tutor and receive response.
     */
    public function send(Request $request, ChatThread $thread): JsonResponse|RedirectResponse
    {
        if ($thread->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessageText = $request->input('message');

        // Save User Message
        ChatMessage::create([
            'chat_thread_id' => $thread->id,
            'role' => 'user',
            'message' => $userMessageText,
        ]);

        // Get conversation history for context
        $history = $thread->messages()
            ->orderBy('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($msg) => ['role' => $msg->role, 'content' => $msg->message])
            ->toArray();

        // Query AI Service
        $replyText = $this->aiTutor->chatReply(new ChatRequestDTO($userMessageText, $thread->id, $history));

        // Save Assistant Message
        $aiMsg = ChatMessage::create([
            'chat_thread_id' => $thread->id,
            'role' => 'assistant',
            'message' => $replyText,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'reply' => $aiMsg->message,
            ]);
        }

        return redirect()->route('chat.index');
    }
}
