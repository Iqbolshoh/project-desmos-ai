@php
    $xpIntoLevel = $studentProfile->xp % 500;
    $xpPercent = (int) round(($xpIntoLevel / 500) * 100);
@endphp

<div class="card p-5">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Daraja</p>
            <p class="mt-2 text-3xl font-extrabold gold-text">{{ $studentProfile->level }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--gold-soft); border: 1px solid var(--gold-border);">
            <x-lucide-award class="w-5 h-5" style="color: var(--gold);" />
        </div>
    </div>
    <div class="mt-4">
        <div class="h-1.5 rounded-full bg-[var(--bg-overlay)] overflow-hidden">
            <div class="h-full rounded-full" style="width: {{ $xpPercent }}%; background: linear-gradient(90deg, var(--gold), var(--gold-alt));"></div>
        </div>
        <p class="mt-2 text-xs text-[var(--text-muted)]">{{ $studentProfile->xp }} XP · keyingi darajagacha {{ 500 - $xpIntoLevel }} XP</p>
    </div>
</div>
