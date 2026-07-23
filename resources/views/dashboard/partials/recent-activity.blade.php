<div class="card p-6 border-[var(--border-strong)] rounded-2xl h-full flex flex-col">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-8 h-8 rounded-lg bg-[var(--accent-soft)] border border-[var(--accent-border)] flex items-center justify-center">
            <x-lucide-activity class="w-4 h-4 text-[var(--accent)]" />
        </div>
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">So'nggi faoliyat</h3>
    </div>

    <div class="flex-1 flex flex-col justify-center">
        @forelse ($recentAttempts as $attempt)
        <div class="flex items-center gap-4 py-3 {{ ! $loop->last ? 'border-b border-[var(--border-subtle)]' : '' }} group hover:bg-[var(--bg-overlay)] px-3 -mx-3 rounded-xl transition-colors cursor-default">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border transition-transform group-hover:scale-105
                {{ $attempt->is_correct ? 'bg-emerald-500/10 border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.1)]' : 'bg-red-500/10 border-red-500/20 shadow-[0_0_10px_rgba(239,68,68,0.1)]' }}">
                @if ($attempt->is_correct)
                    <x-lucide-check class="w-5 h-5 text-emerald-400" />
                @else
                    <x-lucide-x class="w-5 h-5 text-red-400" />
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-white truncate group-hover:text-[var(--accent-hover)] transition-colors">{{ $attempt->question->topic->name ?? 'Masala' }}</p>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">{{ $attempt->created_at->diffForHumans() }}</p>
            </div>
            @if ($attempt->xp_earned > 0)
            <span class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-bold bg-[var(--gold-soft)] border border-[var(--gold-border)] text-[var(--gold)]">
                +{{ $attempt->xp_earned }} XP
            </span>
            @endif
        </div>
        @empty
        <div class="text-center py-6">
            <div class="w-16 h-16 rounded-full bg-[var(--bg-overlay)] flex items-center justify-center mx-auto mb-3">
                <x-lucide-inbox class="w-8 h-8 text-[var(--text-muted)] opacity-50" />
            </div>
            <p class="text-sm text-[var(--text-secondary)]">Hali faoliyat yo'q. Amaliyotni boshlang!</p>
        </div>
        @endforelse
    </div>
</div>
