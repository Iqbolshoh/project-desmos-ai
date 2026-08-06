<div class="card p-6 border-[var(--border-strong)] rounded-2xl shadow-xl">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-white flex items-center gap-2 text-base">
            <x-lucide-target class="w-5 h-5 text-[var(--gold)]" />
            SAT Score Goal
        </h3>
        <span class="text-xs text-[var(--text-muted)] font-mono">Target Benchmark</span>
    </div>

    <div class="space-y-4">
        <div class="flex justify-between items-baseline">
            <div>
                <span class="text-xs text-[var(--text-muted)] uppercase tracking-wider block font-mono">Current Score</span>
                <span class="text-2xl font-extrabold text-white font-mono">{{ $studentProfile->sat_current_score ?? 400 }}</span>
            </div>
            <div class="text-right">
                <span class="text-xs text-[var(--text-muted)] uppercase tracking-wider block font-mono">Target Goal</span>
                <span class="text-2xl font-extrabold text-[var(--gold)] font-mono">{{ $studentProfile->sat_goal_score ?? 800 }}</span>
            </div>
        </div>

        @php
            $current = $studentProfile->sat_current_score ?? 400;
            $goal = $studentProfile->sat_goal_score ?? 800;
            $percent = min(100, max(0, round(($current / $goal) * 100)));
        @endphp

        <div class="w-full bg-black/50 rounded-full h-3 border border-[var(--border-subtle)] overflow-hidden">
            <div class="bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] h-3 rounded-full transition-all duration-1000"
                 style="width: {{ $percent }}%"></div>
        </div>

        <p class="text-xs text-[var(--text-secondary)] text-center">
            {{ max(0, $goal - $current) }} points remaining to reach your SAT target!
        </p>
    </div>
</div>
