<div class="card p-6 border-[var(--border-strong)] rounded-2xl hover:border-orange-500/30 transition-all duration-300 group hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-500/10">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] group-hover:text-orange-400 transition-colors">Kunlik streak</p>
            <p class="mt-2 text-4xl font-extrabold text-white">{{ $studentProfile->streak_current }} <span class="text-lg font-bold text-[var(--text-muted)]">kun</span></p>
        </div>
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-orange-500/10 border border-orange-500/20 group-hover:scale-110 transition-transform duration-300">
            <x-lucide-flame class="w-6 h-6 text-orange-400" />
        </div>
    </div>
    <div class="mt-5 flex items-center gap-2">
        @if ($studentProfile->streak_current > 0)
            <div class="px-2.5 py-1 bg-orange-500/10 border border-orange-500/20 rounded-lg text-orange-400 text-xs font-bold flex items-center gap-1.5 w-fit">
                <x-lucide-trending-up class="w-3.5 h-3.5" />
                Rekord: {{ $studentProfile->streak_longest }} kun
            </div>
        @else
            <p class="text-xs text-[var(--text-muted)]">Bugun mashq qilib streakni boshlang</p>
        @endif
    </div>
</div>
