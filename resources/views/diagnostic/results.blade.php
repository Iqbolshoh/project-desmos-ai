@extends('layouts.dashboard')

@section('title', 'Diagnostic Results')

@section('content')
<div class="max-w-5xl mx-auto space-y-8" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">

    {{-- Hero Celebration --}}
    <div class="text-center py-12 relative overflow-hidden card border-[var(--gold-border)] bg-gradient-to-br from-[var(--bg-raised)] via-[var(--bg-surface)] to-black rounded-3xl shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-br from-[var(--gold-soft)] to-transparent pointer-events-none"></div>
        <div class="absolute -top-8 -right-8 opacity-5 rotate-12">
            <x-lucide-award class="w-64 h-64 text-[var(--gold)]" />
        </div>
        <div class="relative z-10">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-[var(--gold-soft)] border-4 border-[var(--gold-border)] mb-6 shadow-2xl shadow-[var(--gold-glow)]">
                <x-lucide-award class="w-12 h-12 text-[var(--gold)]" />
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-[var(--gold-alt)] to-[var(--gold)] mb-3">
                Congratulations!
            </h1>
            <p class="text-[var(--text-secondary)] text-lg">Your diagnostic math assessment is complete.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Score Card --}}
        <div class="card p-8 border-[var(--gold-border)] bg-gradient-to-b from-[var(--gold-soft)] to-black/50 text-center relative overflow-hidden md:col-span-1 rounded-2xl shadow-xl">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <x-lucide-bar-chart-2 class="w-24 h-24 text-[var(--gold)]" />
            </div>
            <h3 class="text-[var(--gold)] font-bold text-xs uppercase tracking-widest mb-4 relative z-10 font-mono">Estimated SAT Math Score</h3>
            <div class="text-7xl font-black text-white my-4 relative z-10 font-mono drop-shadow-lg" style="text-shadow: 0 0 30px rgba(212,175,55,0.4)">
                {{ $result->overall_score_estimate }}<span class="text-2xl text-[var(--text-muted)]">/800</span>
            </div>
            <p class="text-sm text-white/60 relative z-10">
                Out of {{ $result->total_questions }} questions<br>
                <span class="text-[var(--gold)] font-bold text-lg">{{ $result->correct_count }}</span> answered correctly
            </p>
        </div>

        {{-- Domain Breakdown --}}
        <div class="card p-6 border-[var(--border-strong)] md:col-span-2 rounded-2xl shadow-xl">
            <h3 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                <x-lucide-pie-chart class="w-5 h-5 text-[var(--accent)]" />
                Domain-by-Domain Analysis
            </h3>
            <div class="space-y-5">
                @foreach($result->breakdown as $domain => $stats)
                    @if($stats['total'] > 0)
                        @php
                            $percent = round(($stats['correct'] / $stats['total']) * 100);
                            $color = $percent > 75 ? 'from-emerald-500 to-emerald-400' : ($percent > 50 ? 'from-yellow-500 to-amber-400' : 'from-red-500 to-rose-400');
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-white font-semibold capitalize">{{ str_replace('-', ' ', $domain) }}</span>
                                <span class="text-[var(--text-secondary)] font-mono">{{ $stats['correct'] }}/{{ $stats['total'] }} · <span class="font-bold {{ $percent > 75 ? 'text-emerald-400' : ($percent > 50 ? 'text-amber-400' : 'text-red-400') }}">{{ $percent }}%</span></span>
                            </div>
                            <div class="w-full bg-black/50 rounded-full h-3 border border-[var(--border-subtle)] overflow-hidden">
                                <div class="bg-gradient-to-r {{ $color }} h-3 rounded-full transition-all duration-1000 ease-out"
                                     :style="mounted ? 'width: {{ $percent }}%' : 'width: 0'"
                                     style="width: 0; transition: width 1s ease-out {{ $loop->index * 200 }}ms"></div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Weakness Summary & CTA --}}
    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl flex flex-col md:flex-row gap-6 items-center shadow-xl">
        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
            <x-lucide-alert-triangle class="w-8 h-8 text-red-400" />
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="text-white font-bold text-lg">Primary Focus Area</h3>
            <p class="text-[var(--text-secondary)] mt-1">{{ $result->weakness_summary }}</p>
        </div>
        <div class="flex-shrink-0 flex gap-3 flex-col sm:flex-row w-full md:w-auto">
            <a href="{{ route('practice.index') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-[var(--border-strong)] text-white font-semibold rounded-xl hover:bg-[var(--bg-raised)] transition-colors">
                <x-lucide-dumbbell class="w-4 h-4" /> Practice Bank
            </a>
            <a href="{{ route('roadmap.show') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] text-black font-extrabold rounded-xl shadow-lg shadow-[var(--gold-glow)] hover:brightness-110 transition-all">
                Study Roadmap <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>
</div>
@endsection
