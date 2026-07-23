@extends('layouts.dashboard')

@section('title', 'Peshqadamlar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="text-center py-6 relative overflow-hidden card border-[var(--border-strong)]">
        <!-- Gold bg effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-[var(--gold)]/10 to-transparent pointer-events-none"></div>
        <div class="absolute -top-10 -right-10 opacity-10">
            <x-lucide-trophy class="w-48 h-48 text-[var(--gold)]" />
        </div>

        <div class="w-20 h-20 mx-auto rounded-full bg-[var(--gold)]/10 border-4 border-[var(--gold)] flex items-center justify-center mb-4 relative z-10 shadow-[0_0_20px_rgba(255,191,0,0.3)]">
            <x-lucide-crown class="w-10 h-10 text-[var(--gold)] drop-shadow" />
        </div>
        <h1 class="text-3xl font-extrabold text-white relative z-10 mb-2">Peshqadamlar Jadvali</h1>
        <p class="text-[var(--text-secondary)] relative z-10">Eng ko'p tajriba ball (XP) yig'gan Top 10 talaba.</p>
    </div>

    <div class="card p-2 border-[var(--border-strong)] bg-[var(--bg-overlay)]">
        @if($topUsers->isEmpty())
            <div class="text-center py-10 text-[var(--text-muted)]">
                Hozircha peshqadamlar yo'q. Birinchilardan bo'ling!
            </div>
        @else
            <div class="flex flex-col gap-2">
                @foreach($topUsers as $index => $user)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-[var(--border-subtle)] {{ $index < 3 ? 'bg-gradient-to-r from-[var(--bg-raised)] to-transparent' : 'bg-black/30' }} hover:border-[var(--accent-soft)] transition-colors">
                        
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <!-- Rank -->
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold font-mono text-sm shrink-0 shadow-sm
                                @if($index === 0) bg-yellow-500 text-black
                                @elseif($index === 1) bg-gray-300 text-black
                                @elseif($index === 2) bg-[#cd7f32] text-white
                                @else bg-[var(--bg-surface)] text-[var(--text-muted)] border border-[var(--border-strong)]
                                @endif
                            ">
                                {{ $index + 1 }}
                            </div>
                            
                            <!-- Avatar & Name -->
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center text-white font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            
                            <div>
                                <div class="text-white font-bold flex items-center gap-2">
                                    {{ $user->name }}
                                    @if(auth()->id() === $user->id)
                                        <span class="badge badge-accent text-[0.65rem] py-0 px-2 h-4">Siz</span>
                                    @endif
                                </div>
                                <div class="text-[var(--text-secondary)] text-xs flex gap-3 mt-1">
                                    <span class="flex items-center gap-1">
                                        <x-lucide-star class="w-3 h-3 text-[var(--gold)]" /> {{ $user->level }} Daraja
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="hidden md:flex items-center gap-8 text-right">
                            <div>
                                <div class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-0.5">Streak</div>
                                <div class="text-white font-bold flex items-center gap-1 justify-end">
                                    <x-lucide-flame class="w-4 h-4 text-orange-500" />
                                    {{ $user->streak }}
                                </div>
                            </div>
                            <div class="w-[100px]">
                                <div class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-0.5">XP</div>
                                <div class="text-white font-bold text-lg font-mono tracking-tight text-[var(--accent-hover)] drop-shadow-[0_0_8px_var(--accent-glow)]">
                                    {{ number_format($user->xp) }}
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
