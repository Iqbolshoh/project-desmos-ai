@php
    $current = $studentProfile->sat_current_score;
    $goal = $studentProfile->sat_goal_score;
    $scoreProgress = ($current && $goal && $goal > $current)
        ? (int) round((($current - 200) / ($goal - 200)) * 100)
        : null;
@endphp

<div class="card p-6 border-[var(--border-strong)] rounded-2xl hover:border-[var(--accent-border)] transition-all duration-300 group hover:-translate-y-1 hover:shadow-xl hover:shadow-[var(--accent-glow)]">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] group-hover:text-[var(--accent-hover)] transition-colors">SAT Maqsad</p>
            @if ($current && $goal)
                <p class="mt-2 text-4xl font-extrabold text-white">{{ $current }}<span class="text-lg font-bold text-[var(--text-muted)]">/{{ $goal }}</span></p>
            @else
                <p class="mt-2 text-xl font-extrabold text-white">Diagnostika kutilmoqda</p>
            @endif
        </div>
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-[var(--accent-soft)] to-[var(--accent-soft)] border border-[var(--accent-border)] group-hover:scale-110 transition-transform duration-300">
            <x-lucide-target class="w-6 h-6 text-[var(--accent)]" />
        </div>
    </div>

    @if (! is_null($scoreProgress))
    <div class="mt-5">
        <div class="flex justify-between text-xs font-semibold mb-2">
            <span class="text-[var(--accent-hover)]">{{ $scoreProgress }}% yakunlandi</span>
            <span class="text-[var(--text-muted)]">{{ $goal - $current }} ball qoldi</span>
        </div>
        <div class="h-2 rounded-full bg-[var(--bg-overlay)] overflow-hidden border border-[var(--border-subtle)]">
            <div class="h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ max(0, min(100, $scoreProgress)) }}%; background: linear-gradient(90deg, var(--accent), var(--accent-alt)); box-shadow: 0 0 10px var(--accent-glow);"></div>
        </div>
    </div>
    @else
    <p class="mt-5 text-sm text-[var(--text-muted)]">Boshlang'ich ballingizni bilish uchun <a href="{{ route('diagnostic.start') }}" class="text-[var(--accent-hover)] underline">diagnostikadan o'ting</a>.</p>
    @endif
</div>
