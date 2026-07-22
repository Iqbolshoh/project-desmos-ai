<div class="card p-6">
    <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">So'nggi faoliyat</h3>

    @forelse ($recentAttempts as $attempt)
    <div class="flex items-center gap-3 py-3 {{ ! $loop->last ? 'border-b border-[var(--border-subtle)]' : '' }}">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
             style="background: {{ $attempt->is_correct ? 'rgba(52,211,153,0.1)' : 'rgba(248,113,113,0.1)' }}; border: 1px solid {{ $attempt->is_correct ? 'rgba(52,211,153,0.25)' : 'rgba(248,113,113,0.25)' }};">
            @if ($attempt->is_correct)
                <x-lucide-check class="w-4 h-4 text-[var(--success)]" />
            @else
                <x-lucide-x class="w-4 h-4 text-red-400" />
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-[var(--text-primary)] truncate">{{ $attempt->question->topic->name ?? 'Masala' }}</p>
            <p class="text-xs text-[var(--text-muted)]">{{ $attempt->created_at->diffForHumans() }}</p>
        </div>
        @if ($attempt->xp_earned > 0)
        <span class="badge badge-gold shrink-0">+{{ $attempt->xp_earned }} XP</span>
        @endif
    </div>
    @empty
    <div class="text-center py-8">
        <x-lucide-inbox class="w-8 h-8 mx-auto text-[var(--text-muted)]" />
        <p class="mt-3 text-sm text-[var(--text-muted)]">Hali faoliyat yo'q. Amaliyotni boshlang!</p>
    </div>
    @endforelse
</div>
