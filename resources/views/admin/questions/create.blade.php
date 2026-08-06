@extends('layouts.dashboard')

@section('title', 'Add New Question')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.questions.index') }}" class="btn-secondary px-3">
            <x-lucide-arrow-left class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-plus-circle class="w-7 h-7 text-[var(--accent)]" />
            Add New Question
        </h1>
    </div>

    <form action="{{ route('admin.questions.store') }}" method="POST" class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] space-y-6 rounded-2xl shadow-xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Topic</label>
                <select name="topic_id" class="input w-full bg-black/50" required>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->name }} ({{ $topic->domain }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Question Type</label>
                <select name="type" class="input w-full bg-black/50" required>
                    <option value="mcq">Multiple Choice (MCQ)</option>
                    <option value="free_response">Free Response</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Difficulty Level</label>
                <select name="difficulty" class="input w-full bg-black/50" required>
                    <option value="easy">Easy</option>
                    <option value="medium" selected>Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>

            <div class="flex items-center pt-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_diagnostic" value="1" class="w-5 h-5 rounded border-[var(--border-strong)] bg-black/50 text-[var(--accent)] focus:ring-[var(--accent)]">
                    <span class="text-sm font-medium text-white">Include in Diagnostic Test Pool</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Question Prompt</label>
            <textarea name="prompt" rows="3" class="input w-full bg-black/50 font-mono text-sm" placeholder="e.g. If 2x = 4, find x." required></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Correct Answer</label>
                <input type="text" name="correct_answer" class="input w-full bg-black/50" placeholder="e.g. C or 2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">MCQ Options (JSON)</label>
                <input type="text" name="options" class="input w-full bg-black/50 font-mono text-xs" placeholder='{"A":"1", "B":"2", "C":"3", "D":"4"}'>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Explanation & Solution Steps</label>
            <textarea name="explanation" rows="2" class="input w-full bg-black/50 font-mono text-sm" placeholder="Explain step-by-step solution..."></textarea>
        </div>

        <div class="pt-4 border-t border-[var(--border-subtle)] flex justify-end">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <x-lucide-save class="w-4 h-4" /> Save Question
            </button>
        </div>
    </form>
</div>
@endsection
