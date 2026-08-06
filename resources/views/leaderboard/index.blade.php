@extends('layouts.dashboard')

@section('title', 'Student Leaderboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Hero --}}
    <div class="relative overflow-hidden card border-[var(--gold-border)] bg-gradient-to-br from-[var(--bg-raised)] to-black rounded-3xl py-12 text-center shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-br from-[var(--gold-soft)] to-transparent pointer-events-none"></div>
        <div class="absolute -top-10 -right-10 opacity-5 rotate-12">
            <x-lucide-trophy class="w-72 h-72 text-[var(--gold)]" />
        </div>
        <div class="relative z-10">
            <div class="w-20 h-20 mx-auto rounded-full bg-[var(--gold-soft)] border-4 border-[var(--gold-border)] flex items-center justify-center mb-5 shadow-2xl shadow-[var(--gold-glow)]">
                <x-lucide-crown class="w-10 h-10 text-[var(--gold)]" />
            </div>
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-[var(--gold-alt)] to-[var(--gold)] mb-2">
                Student Leaderboard
            </h1>
            <p class="text-[var(--text-secondary)]">Top performing students ranked by total earned Experience Points (XP).</p>
        </div>
    </div>

    {{-- Top 3 Podium --}}
    @if($topUsers->count() >= 3)
    <div class="grid grid-cols-3 gap-4 items-end">
        {{-- 2nd place --}}
        <div class="text-center group">
            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-slate-300 to-slate-500 flex items-center justify-center text-white font-bold text-xl mb-3 border-4 border-slate-400/30 shadow-xl group-hover:scale-110 transition-transform duration-300">
                {{ strtoupper(substr($topUsers[1]->name, 0, 1)) }}
            </div>
            <div class="text-white font-bold text-sm truncate">{{ $topUsers[1]->name }}</div>
            <div class="text-[var(--text-muted)] text-xs">{{ number_format($topUsers[1]->xp) }} XP</div>
            <div class="mt-3 h-20 bg-gradient-to-t from-slate-600/30 to-slate-400/10 border border-slate-400/20 rounded-t-xl flex items-end justify-center pb-2">
                <span class="text-slate-300 font-black text-2xl">2</span>
            </div>
        </div>
        {{-- 1st place --}}
        <div class="text-center group">
            <div class="relative mb-3">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-[var(--gold)] to-[var(--gold-deep)] flex items-center justify-center text-white font-bold text-2xl border-4 border-[var(--gold-border)] shadow-2xl shadow-[var(--gold-glow)] group-hover:scale-110 transition-transform duration-300">
                    {{ strtoupper(substr($topUsers[0]->name, 0, 1)) }}
                </div>
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 text-2xl">👑</div>
            </div>
            <div class="text-[var(--gold)] font-bold truncate">{{ $topUsers[0]->name }}</div>
            <div class="text-[var(--text-muted)] text-xs">{{ number_format($topUsers[0]->xp) }} XP</div>
            <div class="mt-3 h-32 bg-gradient-to-t from-[var(--gold-soft)] to-transparent border border-[var(--gold-border)] rounded-t-xl flex items-end justify-center pb-2">
                <span class="text-[var(--gold)] font-black text-3xl">1</span>
            </div>
        </div>
        {{-- 3rd place --}}
        <div class="text-center group">
            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-amber-700 to-amber-900 flex items-center justify-center text-white font-bold text-xl mb-3 border-4 border-amber-700/30 shadow-xl group-hover:scale-110 transition-transform duration-300">
                {{ strtoupper(substr($topUsers[2]->name, 0, 1)) }}
            </div>
            <div class="text-white font-bold text-sm truncate">{{ $topUsers[2]->name }}</div>
            <div class="text-[var(--text-muted)] text-xs">{{ number_format($topUsers[2]->xp) }} XP</div>
            <div class="mt-3 h-14 bg-gradient-to-t from-amber-900/30 to-amber-700/10 border border-amber-700/20 rounded-t-xl flex items-end justify-center pb-2">
                <span class="text-amber-600 font-black text-2xl">3</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Full list --}}
    <div class="card p-4 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl space-y-4">
        @if($topUsers->isEmpty())
            <div class="text-center py-10 text-[var(--text-muted)]">No leaderboard rankings yet. Be the first to earn XP!</div>
        @else
            <div class="flex flex-col divide-y divide-[var(--border-subtle)]">
                @foreach($topUsers as $index => $user)
                    <div class="flex items-center justify-between p-4 rounded-xl {{ auth()->id() === $user->id ? 'bg-[var(--gold-soft)] border border-[var(--gold-border)]' : 'hover:bg-white/5' }} transition-colors group">

                        <div class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold font-mono text-sm shrink-0 shadow
                                @if($index === 0) bg-gradient-to-br from-yellow-400 to-yellow-600 text-black
                                @elseif($index === 1) bg-gradient-to-br from-slate-300 to-slate-500 text-black
                                @elseif($index === 2) bg-gradient-to-br from-amber-600 to-amber-800 text-white
                                @else bg-[var(--bg-surface)] text-[var(--text-muted)] border border-[var(--border-strong)]
                                @endif">
                                @if($index < 3) {{ ['🥇','🥈','🥉'][$index] }} @else {{ $index + 1 }} @endif
                            </div>

                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center text-white font-bold text-lg shrink-0 shadow-md">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="text-white font-bold flex items-center gap-2">
                                    {{ $user->name }}
                                    @if(auth()->id() === $user->id)
                                        <span class="text-[10px] px-2 py-0.5 bg-[var(--gold)] text-black font-bold rounded-full">You</span>
                                    @endif
                                </div>
                                <div class="text-[var(--text-secondary)] text-xs flex gap-3 mt-0.5">
                                    <span class="flex items-center gap-1">
                                        <x-lucide-star class="w-3 h-3 text-[var(--gold)]" /> Level {{ $user->level }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <x-lucide-flame class="w-3 h-3 text-orange-400" /> {{ $user->streak }} day streak
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-[var(--gold)] font-black text-xl font-mono tabular-nums drop-shadow-[0_0_8px_rgba(212,175,55,0.5)]">
                                {{ number_format($user->xp) }}
                            </div>
                            <div class="text-[10px] text-[var(--text-muted)] uppercase tracking-widest">XP</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-2 border-t border-[var(--border-subtle)]">
                {{ $topUsers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
