<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiTutor\Contracts\AiTutorServiceInterface;
use App\Services\AiTutor\DTO\SolveRequestDTO;
use App\Models\AiTutorSession;

class TutorController extends Controller
{
    protected AiTutorServiceInterface $tutorService;

    public function __construct(AiTutorServiceInterface $tutorService)
    {
        $this->tutorService = $tutorService;
    }

    public function index()
    {
        return view('tutor.index');
    }

    public function solve(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tutor_images', 'public');
        }

        $dto = new SolveRequestDTO($request->input('query'), $imagePath);
        $response = $this->tutorService->solve($dto);

        $session = AiTutorSession::create([
            'user_id' => auth()->id(),
            'input_type' => $request->hasFile('image') ? 'image' : 'text',
            'input_text' => $request->input('query'),
            'input_image_path' => $imagePath,
            'ai_response' => [
                'finalAnswer' => $response->finalAnswer,
                'explanation' => $response->explanation,
                'steps' => array_map(fn($step) => [
                    'stepNumber' => $step->stepNumber,
                    'title' => $step->title,
                    'explanation' => $step->explanation,
                    'mathExpression' => $step->mathExpression
                ], $response->steps),
            ],
            'desmos_state' => ['expression' => $response->graphExpression],
            'driver' => config('services.ai_tutor.driver', 'mock'),
        ]);

        return redirect()->route('tutor.show', $session->id);
    }

    public function show(AiTutorSession $session)
    {
        // Ensure the user can only see their own sessions
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tutor.show', compact('session'));
    }
}
