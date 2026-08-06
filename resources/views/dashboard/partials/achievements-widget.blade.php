<div class="card p-6 border-[var(--border-strong)] rounded-2xl shadow-xl">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-white flex items-center gap-2 text-base">
            <x-lucide-award class="w-5 h-5 text-[var(--gold)]" />
            Achievements
        </h3>
        <span class="text-xs text-[var(--text-muted)] font-mono">Recently Unlocked</span>
    </div>

    @if($earnedAchievements && $earnedAchievements->count() > 0)
        <div class="grid grid-cols-2 gap-3">
            @foreach($earnedAchievements as $ua)
                <div class="p-3 bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-xl flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[var(--gold-soft)] border border-[var(--gold-border)] flex items-center justify-center shrink-0">
                        <x-dynamic-component :component="'lucide-' . ($ua->achievement->icon ?? 'award')" class="w-5 h-5 text-[var(--gold)]" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ $ua->achievement->name }}</p>
                        <p class="text-[10px] text-[var(--gold)] font-mono">+{{ $ua->achievement->xp_reward }} XP</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-6 text-xs text-[var(--text-muted)] border border-dashed border-[var(--border-subtle)] rounded-xl">
            No achievements unlocked yet. Keep practicing!
        </div>
    @endif
</div>
