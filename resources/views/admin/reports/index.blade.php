@extends('layouts.dashboard')

@section('title', 'Tizim Hisobotlari')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
        <x-lucide-file-bar-chart class="w-7 h-7 text-[var(--accent)]" />
        Hisobotlar
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Foydalanuvchilar</p>
            <p class="mt-2 text-3xl font-extrabold text-white">{{ $totalUsers }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">O'tilgan diagnostikalar</p>
            <p class="mt-2 text-3xl font-extrabold text-white">{{ $diagnosticsTaken }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">O'rtacha ball</p>
            <p class="mt-2 text-3xl font-extrabold text-white">{{ $averageScore ?: '—' }}</p>
        </div>
    </div>

    <div class="card bg-[var(--bg-overlay)] border-[var(--border-strong)] overflow-hidden">
        <table class="w-full text-left text-sm text-[var(--text-secondary)]">
            <thead class="bg-black/40 text-[var(--text-muted)] font-mono text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Mavzu</th>
                    <th class="px-6 py-4">Urinishlar</th>
                    <th class="px-6 py-4">To'g'ri</th>
                    <th class="px-6 py-4">Aniqlik</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border-subtle)]">
                @foreach($topicStats as $stat)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 font-medium text-white flex items-center gap-2">
                        <x-dynamic-component :component="'lucide-' . ($stat['topic']->icon ?? 'help-circle')" class="w-4 h-4 text-[var(--accent)]" />
                        {{ $stat['topic']->name }}
                    </td>
                    <td class="px-6 py-4">{{ $stat['total'] }}</td>
                    <td class="px-6 py-4">{{ $stat['correct'] }}</td>
                    <td class="px-6 py-4">
                        @if(is_null($stat['accuracy']))
                            <span class="text-[var(--text-muted)]">—</span>
                        @else
                            <span class="badge {{ $stat['accuracy'] >= 60 ? 'badge-accent' : 'bg-red-500/10 text-red-400' }}">{{ $stat['accuracy'] }}%</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
