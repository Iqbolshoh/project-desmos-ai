@extends('layouts.dashboard')

@section('title', 'Diagnostic Placement Test')

@section('content')
<div class="max-w-3xl mx-auto mt-10 text-center space-y-6 relative group">
    <div class="absolute -inset-x-20 -inset-y-10 bg-gradient-to-r from-[var(--gold-soft)] to-[var(--accent-soft)] blur-3xl opacity-30 rounded-full pointer-events-none group-hover:opacity-50 transition duration-700"></div>

    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-[var(--bg-surface)] to-[var(--bg-raised)] flex items-center justify-center border-2 border-[var(--gold-border)] mb-6 shadow-xl shadow-[var(--gold-glow)] relative z-10 transition-transform duration-500 hover:scale-110">
        <x-lucide-target class="w-12 h-12 text-[var(--gold)]" />
    </div>

    <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-[var(--gold-alt)] to-[var(--gold)] relative z-10">
        Benchmark Your SAT Math Skills
    </h1>
    <p class="text-[var(--text-secondary)] text-lg md:text-xl leading-relaxed max-w-2xl mx-auto relative z-10">
        Take a short diagnostic placement test to estimate your current score and unlock your personalized study roadmap.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 text-left relative z-10">
        <div class="card p-6 bg-white/5 backdrop-blur-md border border-[var(--border-subtle)] hover:border-[var(--gold-border)] transition-all duration-300 rounded-2xl text-center group/card shadow-lg">
            <div class="w-14 h-14 mx-auto bg-[var(--accent-soft)] rounded-xl flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300">
                <x-lucide-list-checks class="w-7 h-7 text-[var(--accent)]" />
            </div>
            <div class="font-bold text-white text-2xl">20 Questions</div>
            <div class="text-xs text-[var(--text-muted)] font-mono uppercase tracking-wider mt-1">Diagnostic Pool</div>
        </div>

        <div class="card p-6 bg-white/5 backdrop-blur-md border border-[var(--border-subtle)] hover:border-[var(--gold-border)] transition-all duration-300 rounded-2xl text-center group/card shadow-lg">
            <div class="w-14 h-14 mx-auto bg-orange-500/10 rounded-xl flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300">
                <x-lucide-clock class="w-7 h-7 text-orange-400" />
            </div>
            <div class="font-bold text-white text-2xl">~15 Mins</div>
            <div class="text-xs text-[var(--text-muted)] font-mono uppercase tracking-wider mt-1">Average Duration</div>
        </div>

        <div class="card p-6 bg-white/5 backdrop-blur-md border border-[var(--border-subtle)] hover:border-[var(--gold-border)] transition-all duration-300 rounded-2xl text-center group/card relative overflow-hidden shadow-lg">
            <div class="w-14 h-14 mx-auto bg-[var(--gold-soft)] rounded-xl flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 relative z-10">
                <x-lucide-trending-up class="w-7 h-7 text-[var(--gold)]" />
            </div>
            <div class="font-bold text-[var(--gold-alt)] text-2xl relative z-10">Roadmap</div>
            <div class="text-xs text-[var(--text-muted)] font-mono uppercase tracking-wider mt-1 relative z-10">Personalized Plan</div>
        </div>
    </div>

    <div class="pt-10 relative z-10">
        <a href="{{ route('diagnostic.show') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] hover:from-[var(--gold)] hover:to-[var(--gold-alt)] text-black font-extrabold text-xl rounded-2xl shadow-2xl shadow-[var(--gold-glow)] transition-all duration-300 hover:-translate-y-1">
            Start Diagnostic Test <x-lucide-arrow-right class="w-6 h-6 animate-pulse" />
        </a>
    </div>
</div>
@endsection
