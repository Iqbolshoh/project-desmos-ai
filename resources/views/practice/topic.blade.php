@extends('layouts.dashboard')

@section('title', $topic->name . ' — Practice')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[var(--accent-soft)] border border-[var(--accent-border)] flex items-center justify-center">
                <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-6 h-6 text-[var(--accent)]" />
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $topic->name }}</h1>
                <p class="text-[var(--text-muted)] text-xs font-mono uppercase tracking-wider">{{ $topic->domain }}</p>
            </div>
        </div>
        <a href="{{ route('practice.index') }}" class="btn-secondary text-sm flex items-center gap-2">
            <x-lucide-arrow-left class="w-4 h-4" /> All Topics
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl flex items-center gap-3">
        <x-lucide-alert-triangle class="w-5 h-5 flex-shrink-0" />
        {{ session('error') }}
    </div>
    @endif

    {{-- Difficulty Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Easy --}}
        <div class="card p-7 text-center border-[var(--border-strong)] flex flex-col h-full bg-gradient-to-b from-[var(--bg-raised)] to-black/30 rounded-2xl group hover:-translate-y-1 transition-all duration-300 hover:border-emerald-500/40 hover:shadow-xl shadow-lg">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                <x-lucide-smile class="w-8 h-8 text-emerald-400" />
            </div>
            <h3 class="font-bold text-white text-xl mb-2">Easy</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-6 flex-1">Reinforce core rules and fundamental skills.</p>
            <div class="text-emerald-400/70 text-sm mb-5 font-mono bg-emerald-500/5 border border-emerald-500/10 rounded-lg py-2">{{ $stats['easy'] }} Questions</div>
            <a href="{{ route('practice.quiz', ['topic' => $topic->slug, 'difficulty' => 'easy']) }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-semibold rounded-xl hover:bg-emerald-500 hover:text-white transition-all duration-200">
                Start Easy <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>

        {{-- Medium --}}
        <div class="card p-7 text-center border-[var(--accent-border)] bg-gradient-to-b from-[var(--bg-raised)] to-black/30 relative overflow-hidden flex flex-col h-full rounded-2xl group hover:-translate-y-1 transition-all duration-300 shadow-xl">
            <div class="absolute top-0 right-0 bg-gradient-to-l from-[var(--accent)] to-[var(--accent-alt)] text-white text-[0.65rem] font-bold px-4 py-1.5 rounded-bl-xl tracking-wider uppercase font-mono">
                Recommended
            </div>
            <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-5 mt-3 group-hover:scale-110 transition-transform duration-300">
                <x-lucide-flame class="w-8 h-8 text-amber-400" />
            </div>
            <h3 class="font-bold text-white text-xl mb-2">Medium</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-6 flex-1">Standard questions matching actual SAT test difficulty.</p>
            <div class="text-amber-400/70 text-sm mb-5 font-mono bg-amber-500/5 border border-amber-500/10 rounded-lg py-2">{{ $stats['medium'] }} Questions</div>
            <a href="{{ route('practice.quiz', ['topic' => $topic->slug, 'difficulty' => 'medium']) }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-alt)] text-white font-bold rounded-xl shadow-lg shadow-[var(--accent-glow)] hover:brightness-110 transition-all duration-200">
                Start Medium <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>

        {{-- Hard --}}
        <div class="card p-7 text-center border-[var(--border-strong)] flex flex-col h-full bg-gradient-to-b from-[var(--bg-raised)] to-black/30 rounded-2xl group hover:-translate-y-1 transition-all duration-300 hover:border-red-500/40 hover:shadow-xl shadow-lg">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                <x-lucide-skull class="w-8 h-8 text-red-400" />
            </div>
            <h3 class="font-bold text-white text-xl mb-2">Hard</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-6 flex-1">Challenging problems designed for 700+ score mastery.</p>
            <div class="text-red-400/70 text-sm mb-5 font-mono bg-red-500/5 border border-red-500/10 rounded-lg py-2">{{ $stats['hard'] }} Questions</div>
            <a href="{{ route('practice.quiz', ['topic' => $topic->slug, 'difficulty' => 'hard']) }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-500/10 border border-red-500/30 text-red-400 font-semibold rounded-xl hover:bg-red-500 hover:text-white transition-all duration-200">
                Start Hard <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>

    </div>
</div>
@endsection
