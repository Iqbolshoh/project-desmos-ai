@extends('layouts.dashboard')

@section('title', 'Yangi Savol Qo\'shish')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.questions.index') }}" class="btn-secondary px-3">
            <x-lucide-arrow-left class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-plus-circle class="w-7 h-7 text-[var(--accent)]" />
            Yangi Savol Qo'shish
        </h1>
    </div>

    <form action="{{ route('admin.questions.store') }}" method="POST" class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Mavzu</label>
                <select name="topic_id" class="input w-full bg-black/50" required>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->name }} ({{ $topic->domain }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Turi</label>
                <select name="type" class="input w-full bg-black/50" required>
                    <option value="mcq">Test (MCQ)</option>
                    <option value="free_response">Ochiq savol (Free Response)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Qiyinlik darajasi</label>
                <select name="difficulty" class="input w-full bg-black/50" required>
                    <option value="easy">Oson</option>
                    <option value="medium" selected>O'rtacha</option>
                    <option value="hard">Qiyin</option>
                </select>
            </div>
            
            <div class="flex items-center pt-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_diagnostic" value="1" class="w-5 h-5 rounded border-[var(--border-strong)] bg-black/50 text-[var(--accent)] focus:ring-[var(--accent)]">
                    <span class="text-sm font-medium text-white">Diagnostika uchun ishlatsinmi?</span>
                </label>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Savol matni (Prompt)</label>
            <textarea name="prompt" rows="3" class="input w-full bg-black/50 font-mono text-sm" placeholder="Masalan: If $2x = 4$, find $x$." required></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">To'g'ri javob</label>
                <input type="text" name="correct_answer" class="input w-full bg-black/50" placeholder="Masalan: C yoki 2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Variantlar (faqat MCQ uchun JSON formatida)</label>
                <input type="text" name="options" class="input w-full bg-black/50 font-mono text-xs" placeholder='{"A":"1", "B":"2", "C":"3", "D":"4"}'>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Tushuntirish (Explanation)</label>
            <textarea name="explanation" rows="2" class="input w-full bg-black/50 font-mono text-sm" placeholder="Javob qanday kelib chiqdi?"></textarea>
        </div>
        
        <div class="pt-4 border-t border-[var(--border-subtle)] flex justify-end">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <x-lucide-save class="w-4 h-4" /> Saqlash
            </button>
        </div>
    </form>
</div>
@endsection
