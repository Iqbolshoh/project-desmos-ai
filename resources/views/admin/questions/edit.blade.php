@extends('layouts.dashboard')

@section('title', 'Edit Question #' . $question->id)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.questions.index') }}" class="btn-secondary px-3">
            <x-lucide-arrow-left class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-pencil class="w-7 h-7 text-[var(--accent)]" />
            Edit Question #{{ $question->id }}
        </h1>
    </div>

    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] space-y-6 rounded-2xl shadow-xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Topic</label>
                <select name="topic_id" class="input w-full bg-black/50" required>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" @selected($topic->id === $question->topic_id)>{{ $topic->name }} ({{ $topic->domain }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Question Type</label>
                <select name="type" class="input w-full bg-black/50" required>
                    <option value="mcq" @selected($question->type === 'mcq')>Multiple Choice (MCQ)</option>
                    <option value="free_response" @selected($question->type === 'free_response')>Free Response</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Difficulty Level</label>
                <select name="difficulty" class="input w-full bg-black/50" required>
                    <option value="easy" @selected($question->difficulty === 'easy')>Easy</option>
                    <option value="medium" @selected($question->difficulty === 'medium')>Medium</option>
                    <option value="hard" @selected($question->difficulty === 'hard')>Hard</option>
                </select>
            </div>

            <div class="flex items-center pt-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_diagnostic" value="1" @checked($question->is_diagnostic) class="w-5 h-5 rounded border-[var(--border-strong)] bg-black/50 text-[var(--accent)] focus:ring-[var(--accent)]">
                    <span class="text-sm font-medium text-white">Include in Diagnostic Test Pool</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Question Prompt</label>
            <textarea name="prompt" rows="3" class="input w-full bg-black/50 font-mono text-sm" required>{{ $question->prompt }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Correct Answer</label>
                <input type="text" name="correct_answer" value="{{ $question->correct_answer }}" class="input w-full bg-black/50" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">MCQ Options (JSON Format)</label>
                <input type="text" name="options" value="{{ $question->options ? (is_array($question->options) ? json_encode($question->options) : $question->options) : '' }}" class="input w-full bg-black/50 font-mono text-xs" placeholder='{"A":"1", "B":"2", "C":"3", "D":"4"}'>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Explanation & Solution Steps</label>
            <textarea name="explanation" rows="2" class="input w-full bg-black/50 font-mono text-sm">{{ $question->explanation }}</textarea>
        </div>

        <div class="pt-4 border-t border-[var(--border-subtle)] flex justify-end">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <x-lucide-save class="w-4 h-4" /> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
