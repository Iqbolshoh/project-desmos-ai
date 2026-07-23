@php
    $xpIntoLevel = $studentProfile->xp % 500;
    $xpPercent = (int) round(($xpIntoLevel / 500) * 100);
@endphp

<div class="card p-6 border-[var(--border-strong)] rounded-2xl hover:border-[var(--gold-border)] transition-all duration-300 group hover:-translate-y-1 hover:shadow-xl hover:shadow-[var(--gold-glow)]">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] group-hover:text-[var(--gold-deep)] transition-colors">Daraja</p>
            <p class="mt-2 text-4xl font-extrabold text-[var(--gold)] drop-shadow-md">{{ $studentProfile->level }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-[var(--gold-soft)] to-[var(--gold-soft)] border border-[var(--gold-border)] group-hover:scale-110 transition-transform duration-300">
            <x-lucide-award class="w-6 h-6 text-[var(--gold)]" />
        </div>
    </div>
    <div class="mt-5">
        <div class="flex justify-between text-xs font-semibold mb-2">
            <span class="text-white">{{ $studentProfile->xp }} XP</span>
            <span class="text-[var(--text-muted)]">{{ 500 - $xpIntoLevel }} XP qoldi</span>
        </div>
        <div class="h-2 rounded-full bg-[var(--bg-overlay)] overflow-hidden border border-[var(--border-subtle)]">
            <div class="h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $xpPercent }}%; background: linear-gradient(90deg, var(--gold-deep), var(--gold), var(--gold-alt)); box-shadow: 0 0 10px var(--gold-glow);"></div>
        </div>
    </div>
</div>
