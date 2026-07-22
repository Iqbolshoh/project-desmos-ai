@php
    $current = $studentProfile->sat_current_score;
    $goal = $studentProfile->sat_goal_score;
    $scoreProgress = ($current && $goal && $goal > $current)
        ? (int) round((($current - 200) / ($goal - 200)) * 100)
        : null;
@endphp

<div class="card p-5">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">SAT maqsad</p>
            @if ($current && $goal)
                <p class="mt-2 text-3xl font-extrabold text-white">{{ $current }} <span class="text-base font-semibold text-[var(--text-muted)]">/ {{ $goal }}</span></p>
            @else
                <p class="mt-2 text-lg font-extrabold text-white">Diagnostika kutilmoqda</p>
            @endif
        </div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--accent-soft); border: 1px solid var(--accent-border);">
            <x-lucide-target class="w-5 h-5" style="color: var(--accent-hover);" />
        </div>
    </div>

    @if (! is_null($scoreProgress))
    <div class="mt-4">
        <div class="h-1.5 rounded-full bg-[var(--bg-overlay)] overflow-hidden">
            <div class="h-full rounded-full" style="width: {{ max(0, min(100, $scoreProgress)) }}%; background: linear-gradient(90deg, var(--accent), var(--accent-alt));"></div>
        </div>
        <p class="mt-2 text-xs text-[var(--text-muted)]">Maqsadgacha {{ $goal - $current }} ball qoldi</p>
    </div>
    @else
    <p class="mt-4 text-xs text-[var(--text-muted)]">Boshlang'ich ballingizni bilish uchun diagnostika testini yeching.</p>
    @endif
</div>
