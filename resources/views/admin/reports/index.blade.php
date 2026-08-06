@extends('layouts.dashboard')

@section('title', 'Platform Reports')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
        <x-lucide-file-bar-chart class="w-7 h-7 text-[var(--accent)]" />
        System & Learning Reports
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] font-mono">Total Users</p>
            <p class="mt-2 text-3xl font-extrabold text-white font-mono">{{ $totalUsers }}</p>
        </div>
        <div class="card p-5 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] font-mono">Diagnostics Completed</p>
            <p class="mt-2 text-3xl font-extrabold text-white font-mono">{{ $diagnosticsTaken }}</p>
        </div>
        <div class="card p-5 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] font-mono">Average Diagnostic Score</p>
            <p class="mt-2 text-3xl font-extrabold text-[var(--gold)] font-mono">{{ $averageScore ?: '—' }}</p>
        </div>
    </div>

    <div class="card bg-[var(--bg-overlay)] border-[var(--border-strong)] overflow-hidden rounded-2xl shadow-xl">
        <table class="w-full text-left text-sm text-[var(--text-secondary)]">
            <thead class="bg-black/40 text-[var(--text-muted)] font-mono text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Topic</th>
                    <th class="px-6 py-4">Total Attempts</th>
                    <th class="px-6 py-4">Correct Answers</th>
                    <th class="px-6 py-4">Accuracy Rate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border-subtle)]">
                @foreach($topicStats as $stat)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 font-medium text-white flex items-center gap-2">
                        <x-dynamic-component :component="'lucide-' . ($stat['topic']->icon ?? 'help-circle')" class="w-4 h-4 text-[var(--accent)]" />
                        {{ $stat['topic']->name }}
                    </td>
                    <td class="px-6 py-4 font-mono">{{ $stat['total'] }}</td>
                    <td class="px-6 py-4 font-mono text-emerald-400">{{ $stat['correct'] }}</td>
                    <td class="px-6 py-4">
                        @if(is_null($stat['accuracy']))
                            <span class="text-[var(--text-muted)]">—</span>
                        @else
                            <span class="badge font-mono {{ $stat['accuracy'] >= 60 ? 'badge-accent' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">{{ $stat['accuracy'] }}%</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
