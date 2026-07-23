<div class="card p-6 border-[var(--border-strong)] rounded-2xl h-full flex flex-col">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[var(--gold-soft)] border border-[var(--gold-border)] flex items-center justify-center">
                <x-lucide-medal class="w-4 h-4 text-[var(--gold)]" />
            </div>
            Yutuqlar
        </h3>
        <a href="{{ route('profile.index') }}" class="text-xs font-bold text-[var(--accent-hover)] hover:text-white transition-colors bg-[var(--accent-soft)] px-3 py-1.5 rounded-lg border border-[var(--accent-border)]">
            Barchasi
        </a>
    </div>

    <div class="flex-1 flex items-center">
        @if ($earnedAchievements->isEmpty())
            <div class="w-full text-center py-4">
                <p class="text-sm text-[var(--text-muted)]">Hozircha yutuqlaringiz yo'q. Mashq qilib ularni oching!</p>
            </div>
        @else
            <div class="flex flex-wrap gap-3">
                @foreach ($earnedAchievements as $userAchievement)
                <div class="group relative flex items-center gap-3 pr-4 pl-2 py-2 rounded-xl border border-[var(--border-strong)] bg-gradient-to-r from-[var(--bg-overlay)] to-transparent hover:border-[var(--gold-border)] transition-all cursor-default">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gradient-to-br from-[var(--gold-soft)] to-transparent border border-[var(--gold-border)] group-hover:scale-110 transition-transform">
                        <x-dynamic-component :component="'lucide-' . $userAchievement->achievement->icon" class="w-4 h-4 text-[var(--gold)]" />
                    </div>
                    <span class="text-sm font-bold text-white">{{ $userAchievement->achievement->name }}</span>

                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-xs px-3 py-2 bg-black text-xs text-white rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-20 border border-[var(--border-strong)] shadow-xl">
                        {{ $userAchievement->achievement->description }}
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
