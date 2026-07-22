<div class="card p-5">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Kunlik streak</p>
            <p class="mt-2 text-3xl font-extrabold text-white">{{ $studentProfile->streak_current }} <span class="text-base font-semibold text-[var(--text-muted)]">kun</span></p>
        </div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.10); border: 1px solid rgba(245,158,11,0.25);">
            <x-lucide-flame class="w-5 h-5" style="color: var(--warning);" />
        </div>
    </div>
    <p class="mt-4 text-xs text-[var(--text-muted)]">
        @if ($studentProfile->streak_current > 0)
            Eng uzun streak: {{ $studentProfile->streak_longest }} kun
        @else
            Bugun mashq qilib streakni boshlang
        @endif
    </p>
</div>
