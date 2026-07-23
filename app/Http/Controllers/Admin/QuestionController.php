<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Topic;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('topic')->paginate(15);
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('admin.questions.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'type' => 'required|string',
            'difficulty' => 'required|string',
            'is_diagnostic' => 'boolean',
            'prompt' => 'required|string',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
        ]);
        
        $validated['is_diagnostic'] = $request->has('is_diagnostic');
        
        Question::create($validated);
        
        return redirect()->route('admin.questions.index')->with('success', 'Savol muvaffaqiyatli qo\'shildi!');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Savol o\'chirildi!');
    }
}
