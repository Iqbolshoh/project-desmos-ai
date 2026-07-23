<div class="card p-5">
    <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Yutuqlar</p>
        <a href="{{ route('profile.index') }}" class="text-xs font-semibold text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors">
            Barchasi
        </a>
    </div>

    @if ($earnedAchievements->isEmpty())
        <p class="text-xs text-[var(--text-muted)]">Hozircha yutuqlaringiz yo'q.</p>
    @else
        <div class="flex flex-wrap gap-3">
            @foreach ($earnedAchievements as $userAchievement)
            <div class="flex items-center gap-2 pr-3 pl-1.5 py-1.5 rounded-lg border border-[var(--border-strong)] bg-[var(--bg-overlay)]" title="{{ $userAchievement->achievement->description }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: var(--accent-soft); border: 1px solid var(--accent-border);">
                    <x-dynamic-component :component="'lucide-' . $userAchievement->achievement->icon" class="w-3.5 h-3.5" style="color: var(--accent-hover);" />
                </div>
                <span class="text-xs font-semibold text-white">{{ $userAchievement->achievement->name }}</span>
            </div>
            @endforeach
        </div>
    @endif
</div>
