<div class="card p-6 border-[var(--border-strong)] rounded-2xl shadow-xl">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-white flex items-center gap-2 text-base">
            <x-lucide-zap class="w-5 h-5 text-[var(--gold)]" />
            Experience Points (XP)
        </h3>
        <span class="badge badge-gold font-mono">Level {{ $studentProfile->level ?? 1 }}</span>
    </div>

    <div class="flex items-baseline gap-2 my-2">
        <span class="text-4xl font-black text-[var(--gold)] font-mono drop-shadow-[0_0_12px_rgba(242,169,59,0.4)]">
            {{ number_format($studentProfile->xp ?? 0, 0, '.', ' ') }}
        </span>
        <span class="text-xs text-[var(--text-muted)] font-mono uppercase tracking-widest">XP Total</span>
    </div>

    <p class="text-xs text-[var(--text-secondary)] mt-2">
        Solve practice problems and complete diagnostic tests to level up!
    </p>
</div>
