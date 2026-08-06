<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Display a paginated list of SAT questions.
     */
    public function index(): View
    {
        $questions = Question::with('topic')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show form for creating a new question.
     */
    public function create(): View
    {
        $topics = Topic::orderBy('sort_order')->get();

        return view('admin.questions.create', compact('topics'));
    }

    /**
     * Store a newly created question in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['is_diagnostic'] = $request->has('is_diagnostic');

        Question::create($validated);

        return redirect()->route('admin.questions.index')->with('success', 'Question created successfully!');
    }

    /**
     * Show form for editing an existing question.
     */
    public function edit(Question $question): View
    {
        $topics = Topic::orderBy('sort_order')->get();

        return view('admin.questions.edit', compact('question', 'topics'));
    }

    /**
     * Update the specified question in database.
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['is_diagnostic'] = $request->has('is_diagnostic');

        $question->update($validated);

        return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified question from database.
     */
    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully!');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'type' => 'required|string',
            'difficulty' => 'required|string',
            'is_diagnostic' => 'boolean',
            'prompt' => 'required|string',
            'correct_answer' => 'required|string',
            'options' => 'nullable|json',
            'explanation' => 'nullable|string',
        ]);
    }
}
