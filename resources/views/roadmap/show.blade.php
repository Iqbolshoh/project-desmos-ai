@extends('layouts.dashboard')

@section('title', 'Roadmap')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-extrabold text-white flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)]">
                <x-lucide-map class="w-6 h-6 text-[var(--accent)]" />
            </div>
            Shaxsiy O'quv Rejasi
        </h1>
        <form action="{{ route('roadmap.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn-secondary text-sm hover:-translate-y-0.5 transition-transform">
                <x-lucide-refresh-cw class="w-4 h-4" /> Qayta tuzish
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl flex items-center gap-3">
        <x-lucide-check-circle class="w-5 h-5 flex-shrink-0" />
        {{ session('success') }}
    </div>
    @endif

    @if($roadmap)

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
            $stats = [
                ['label' => 'Joriy ball', 'value' => $roadmap->current_score, 'icon' => 'gauge', 'color' => 'accent'],
                ['label' => 'Maqsad', 'value' => $roadmap->goal_score, 'icon' => 'target', 'color' => 'gold'],
                ['label' => 'Taxminiy vaqt', 'value' => $roadmap->estimated_weeks.' hafta', 'icon' => 'calendar', 'color' => 'accent'],
                ['label' => 'Kunlik mashq', 'value' => $roadmap->daily_study_minutes.' daq', 'icon' => 'timer', 'color' => 'accent'],
            ];
            @endphp
            @foreach($stats as $s)
            <div class="card p-5 border-[var(--border-strong)] rounded-2xl text-center">
                <div class="text-xs uppercase tracking-widest text-[var(--text-muted)] mb-2">{{ $s['label'] }}</div>
                <div class="text-2xl font-extrabold {{ $s['color'] === 'gold' ? 'text-[var(--gold)]' : 'text-white' }}">
                    {{ $s['value'] }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Progress bar --}}
        <div class="card p-5 border-[var(--border-strong)] rounded-2xl">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-semibold text-white">Umumiy jarayon</span>
                <span class="text-[var(--gold)] font-bold font-mono">{{ $roadmap->completion_percent }}%</span>
            </div>
            <div class="w-full bg-black/50 rounded-full h-3 border border-[var(--border-subtle)] overflow-hidden">
                <div class="bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] h-3 rounded-full transition-all duration-1000"
                     style="width: {{ $roadmap->completion_percent }}%"></div>
            </div>
        </div>

        {{-- Weekly Plan --}}
        <div>
            <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                <x-lucide-calendar-days class="w-5 h-5 text-[var(--accent)]" /> Haftalik reja
            </h2>
            <div class="relative pl-6 space-y-6 before:absolute before:left-3 before:top-0 before:bottom-4 before:w-0.5 before:bg-gradient-to-b before:from-[var(--accent)] before:to-transparent">
                @foreach($roadmap->weekly_plan as $week)
                @php $weekCompleted = collect($week['tasks'])->every(fn($t) => $t['completed']); @endphp
                <div class="relative flex items-start gap-5">
                    {{-- Timeline dot --}}
                    <div class="absolute -left-6 w-7 h-7 rounded-full shrink-0 flex items-center justify-center text-xs font-bold z-10 mt-1 shadow-lg transition-all duration-300
                        {{ $weekCompleted ? 'bg-emerald-500 border-emerald-500 text-white shadow-emerald-500/30' : 'bg-[var(--bg-overlay)] border-2 border-[var(--border-strong)] text-[var(--text-muted)]' }}">
                        @if($weekCompleted)
                            <x-lucide-check class="w-4 h-4" />
                        @else
                            {{ $week['week'] }}
                        @endif
                    </div>

                    {{-- Week card --}}
                    <div class="ml-4 w-full card p-5 border-[var(--border-strong)] rounded-2xl {{ $weekCompleted ? 'opacity-60' : '' }} hover:border-[var(--accent-border)] transition-colors">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-[var(--accent-soft)] text-[var(--accent-hover)] flex items-center justify-center text-xs font-bold">
                                    {{ $week['week'] }}
                                </span>
                                {{ $week['focus'] }}
                            </h3>
                            @if($weekCompleted)
                                <span class="text-xs px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full font-semibold">✓ Bajarildi</span>
                            @endif
                        </div>

                        <div class="space-y-2">
                            @foreach($week['tasks'] as $task)
                            <form action="{{ route('roadmap.toggle', $roadmap->id) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                                <button type="submit"
                                    class="w-full flex items-center gap-3 p-3 rounded-xl border transition-all text-left cursor-pointer group
                                    {{ $task['completed'] ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-black/20 border-[var(--border-subtle)] hover:bg-[var(--bg-overlay)] hover:border-[var(--accent-border)]' }}">
                                    <div class="w-5 h-5 rounded-md flex items-center justify-center border transition-all shrink-0
                                        {{ $task['completed'] ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-[var(--border-strong)] group-hover:border-[var(--accent)] text-transparent' }}">
                                        <x-lucide-check class="w-3.5 h-3.5" />
                                    </div>
                                    <span class="text-sm font-medium {{ $task['completed'] ? 'text-[var(--text-muted)] line-through' : 'text-white' }}">
                                        {{ $task['title'] }}
                                    </span>
                                </button>
                            </form>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Monthly Plan --}}
        <div>
            <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                <x-lucide-calendar-range class="w-5 h-5 text-[var(--gold)]" /> Oylik reja
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($roadmap->monthly_plan as $i => $month)
                <div class="card p-5 border-[var(--border-strong)] rounded-2xl hover:border-[var(--gold-border)] transition-all duration-300 hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-[var(--gold-soft)] border border-[var(--gold-border)] flex items-center justify-center text-xs font-bold text-[var(--gold)]">
                                {{ $month['month'] }}
                            </div>
                            <h3 class="font-bold text-white">{{ $month['month'] }}-oy</h3>
                        </div>
                        <span class="text-xs font-mono px-2.5 py-1 rounded-full bg-[var(--accent-soft)] border border-[var(--accent-border)] text-[var(--accent-hover)]">
                            Maqsad: {{ $month['target_score'] }}
                        </span>
                    </div>
                    <p class="text-sm text-[var(--text-secondary)] leading-relaxed">{{ $month['goal'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

    @else
        {{-- Empty state --}}
        <div class="text-center py-20 card border-dashed border-[var(--border-strong)] rounded-2xl">
            <div class="w-20 h-20 mx-auto rounded-full bg-[var(--bg-overlay)] border border-[var(--border-strong)] flex items-center justify-center mb-6">
                <x-lucide-map class="w-10 h-10 text-[var(--text-muted)] opacity-50" />
            </div>
            <h3 class="text-2xl font-bold text-white mb-3">Sizda o'quv rejasi yo'q</h3>
            <p class="text-[var(--text-secondary)] mb-8 max-w-md mx-auto leading-relaxed">
                Tuzilgan reja ko'nikmalaringizni to'g'ri shakllantirishga yordam beradi. Buning uchun avval diagnostika testidan o'ting.
            </p>
            <a href="{{ route('diagnostic.start') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] text-white font-bold rounded-xl shadow-lg shadow-[var(--gold-glow)] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                <x-lucide-target class="w-5 h-5" /> Diagnostika testini boshlash
            </a>
        </div>
    @endif
</div>
@endsection
