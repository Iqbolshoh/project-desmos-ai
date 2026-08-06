<div class="card p-6 border-[var(--border-strong)] rounded-2xl shadow-xl">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-white flex items-center gap-2 text-base">
            <x-lucide-flame class="w-5 h-5 text-orange-400" />
            Daily Activity Streak
        </h3>
        <span class="text-xs text-orange-400 font-mono font-bold">{{ $studentProfile->streak_current ?? 0 }} Days Streak</span>
    </div>

    <div class="flex items-baseline gap-2 my-2">
        <span class="text-4xl font-black text-white font-mono">{{ $studentProfile->streak_current ?? 0 }}</span>
        <span class="text-sm text-[var(--text-muted)]">consecutive active days</span>
    </div>

    <p class="text-xs text-[var(--text-secondary)] mt-2">
        Longest streak: <strong class="text-white">{{ $studentProfile->streak_longest ?? 0 }} days</strong>. Practice daily to earn bonus XP!
    </p>
</div>
