@php
    $breakdown = $latestDiagnostic->breakdown ?? null;
@endphp

<div class="card p-6">
    <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Kuchli / zaif mavzular</h3>

    @if ($breakdown)
        <div class="space-y-3">
            @foreach ($breakdown as $domain => $stats)
                @php
                    $total = $stats['total'] ?? 0;
                    $correct = $stats['correct'] ?? 0;
                    $percent = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
                    $isStrong = $percent >= 70;
                @endphp
                <div class="flex items-center gap-3">
                    <x-dynamic-component :component="$isStrong ? 'lucide-trending-up' : 'lucide-trending-down'" class="w-4 h-4 shrink-0 {{ $isStrong ? 'text-[var(--success)]' : 'text-red-400' }}" />
                    <span class="text-sm text-[var(--text-secondary)] flex-1 capitalize">{{ $domain }}</span>
                    <span class="text-xs font-mono text-[var(--text-muted)]">{{ $percent }}%</span>
                </div>
            @endforeach
        </div>
    @else
    <div class="text-center py-8">
        <x-lucide-clipboard-check class="w-8 h-8 mx-auto text-[var(--text-muted)]" />
        <p class="mt-3 text-sm text-[var(--text-muted)]">Kuchli va zaif mavzularni bilish uchun diagnostika testini yeching.</p>
    </div>
    @endif
</div>
