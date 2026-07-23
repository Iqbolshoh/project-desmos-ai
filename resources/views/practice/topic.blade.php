@extends('layouts.dashboard')

@section('title', $topic->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-7 h-7 text-[var(--accent)]" />
            {{ $topic->name }}
        </h1>
        <a href="{{ route('practice.index') }}" class="btn-secondary text-sm">
            <x-lucide-arrow-left class="w-4 h-4" /> Barcha mavzular
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-lg flex items-center gap-3">
        <x-lucide-alert-triangle class="w-5 h-5" />
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Easy -->
        <div class="card p-6 text-center border-[var(--border-strong)] flex flex-col h-full bg-gradient-to-b from-[var(--bg-raised)] to-[var(--bg-overlay)]">
            <div class="w-16 h-16 mx-auto rounded-full bg-green-500/10 border border-green-500/20 flex items-center justify-center mb-4">
                <x-lucide-smile class="w-8 h-8 text-green-400" />
            </div>
            <h3 class="font-bold text-white text-xl mb-1">Oson</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-6 flex-1">Asosiy qoidalarni mustahkamlash.</p>
            <div class="text-[var(--text-muted)] text-sm mb-4 font-mono">{{ $stats['easy'] }} ta savol</div>
            <a href="{{ route('practice.quiz', ['topic' => $topic->slug, 'difficulty' => 'easy']) }}" class="btn-primary w-full shadow-lg">Boshlash</a>
        </div>

        <!-- Medium -->
        <div class="card p-6 text-center border-[var(--accent-border)] bg-gradient-to-b from-[var(--bg-raised)] to-[var(--bg-overlay)] relative overflow-hidden flex flex-col h-full">
            <div class="absolute inset-0 border-2 border-[var(--accent-soft)] rounded-[var(--radius-lg)] pointer-events-none"></div>
            <div class="absolute top-0 right-0 bg-[var(--accent)] text-white text-[0.65rem] font-bold px-3 py-1 rounded-bl-lg tracking-wider uppercase">Tavsiya etiladi</div>
            
            <div class="w-16 h-16 mx-auto rounded-full bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center mb-4 mt-2">
                <x-lucide-flame class="w-8 h-8 text-yellow-400" />
            </div>
            <h3 class="font-bold text-white text-xl mb-1">O'rtacha</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-6 flex-1">Haqiqiy SAT darajasidagi standart masalalar.</p>
            <div class="text-[var(--text-muted)] text-sm mb-4 font-mono">{{ $stats['medium'] }} ta savol</div>
            <a href="{{ route('practice.quiz', ['topic' => $topic->slug, 'difficulty' => 'medium']) }}" class="btn-primary w-full shadow-lg shadow-[var(--accent-glow)]">Boshlash</a>
        </div>

        <!-- Hard -->
        <div class="card p-6 text-center border-[var(--border-strong)] flex flex-col h-full bg-gradient-to-b from-[var(--bg-raised)] to-[var(--bg-overlay)]">
            <div class="w-16 h-16 mx-auto rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-4">
                <x-lucide-skull class="w-8 h-8 text-red-400" />
            </div>
            <h3 class="font-bold text-white text-xl mb-1">Qiyin</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-6 flex-1">Eng qiyin va ko'p vaqt talab qiladigan masalalar.</p>
            <div class="text-[var(--text-muted)] text-sm mb-4 font-mono">{{ $stats['hard'] }} ta savol</div>
            <a href="{{ route('practice.quiz', ['topic' => $topic->slug, 'difficulty' => 'hard']) }}" class="btn-primary w-full shadow-lg">Boshlash</a>
        </div>
        
    </div>
</div>
@endsection
