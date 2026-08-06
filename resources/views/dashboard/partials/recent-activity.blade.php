<div class="card p-6 border-[var(--border-strong)] rounded-2xl shadow-xl space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-white flex items-center gap-2 text-base">
            <x-lucide-activity class="w-5 h-5 text-[var(--teal)]" />
            Recent Practice Activity
        </h3>
        <a href="{{ route('history.index') }}" class="text-xs text-[var(--teal)] hover:underline font-medium">View All History →</a>
    </div>

    @if($recentAttempts && $recentAttempts->count() > 0)
        <div class="space-y-3">
            @foreach($recentAttempts as $attempt)
                <div class="p-3.5 bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $attempt->is_correct ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border border-red-500/20 text-red-400' }}">
                            @if($attempt->is_correct)
                                <x-lucide-check class="w-4 h-4" />
                            @else
                                <x-lucide-x class="w-4 h-4" />
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white line-clamp-1">{{ $attempt->question->prompt ?? 'Question' }}</p>
                            <p class="text-[10px] text-[var(--text-muted)] font-mono">{{ $attempt->question->topic->name ?? 'SAT Math' }} · {{ ucfirst($attempt->question->difficulty ?? 'medium') }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-mono {{ $attempt->is_correct ? 'text-[var(--gold)] font-bold' : 'text-[var(--text-muted)]' }}">
                        {{ $attempt->is_correct ? '+'.$attempt->xp_earned.' XP' : '0 XP' }}
                    </span>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-xs text-[var(--text-muted)] border border-dashed border-[var(--border-subtle)] rounded-xl">
            No recent practice attempts recorded.
        </div>
    @endif
</div>
