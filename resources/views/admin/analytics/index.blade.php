@extends('layouts.dashboard')

@section('title', 'Platform Analytics')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
        <x-lucide-activity class="w-7 h-7 text-[var(--accent)]" />
        Performance & User Analytics
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] font-mono">Active Users (7 Days)</p>
            <p class="mt-2 text-3xl font-extrabold text-white font-mono">{{ $activeUsersWeek }}</p>
        </div>
        <div class="card p-5 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] font-mono">Total Practice Attempts</p>
            <p class="mt-2 text-3xl font-extrabold text-white font-mono">{{ $practiceAttempts }}</p>
        </div>
        <div class="card p-5 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] font-mono">Overall Practice Accuracy</p>
            <p class="mt-2 text-3xl font-extrabold text-[var(--gold)] font-mono">{{ $practiceAccuracy !== null ? $practiceAccuracy . '%' : '—' }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-4 font-mono">Level Distribution</h3>
            @if($levelDistribution->isEmpty())
                <p class="text-sm text-[var(--text-muted)]">No distribution data recorded.</p>
            @else
                <div class="space-y-2">
                    @php $maxTotal = $levelDistribution->max('total') ?: 1; @endphp
                    @foreach($levelDistribution as $row)
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono text-[var(--text-muted)] w-16">Level {{ $row->level }}</span>
                        <div class="flex-1 h-2 rounded-full bg-black/40 overflow-hidden">
                            <div class="h-full bg-[var(--accent)]" style="width: {{ ($row->total / $maxTotal) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-mono text-white w-6 text-right">{{ $row->total }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-4 font-mono">Top XP Earners</h3>
            @if($topUsers->isEmpty())
                <p class="text-sm text-[var(--text-muted)]">No rankings available.</p>
            @else
                <ol class="space-y-2">
                    @foreach($topUsers as $i => $u)
                    <li class="flex items-center justify-between text-sm py-1 border-b border-[var(--border-subtle)] last:border-0">
                        <span class="text-[var(--text-secondary)]"><span class="font-mono text-[var(--text-muted)] mr-2">#{{ $i + 1 }}</span>{{ $u->name }}</span>
                        <span class="font-mono text-[var(--gold)] font-bold">{{ number_format($u->xp, 0, '.', ' ') }} XP</span>
                    </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
</div>
@endsection
