@php
    $breakdown = $latestDiagnostic->breakdown ?? null;
@endphp

<div class="card p-6 border-[var(--border-strong)] rounded-2xl h-full flex flex-col">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-8 h-8 rounded-lg bg-[var(--accent-soft)] border border-[var(--accent-border)] flex items-center justify-center">
            <x-lucide-bar-chart-2 class="w-4 h-4 text-[var(--accent)]" />
        </div>
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Kuchli / Zaif mavzular</h3>
    </div>

    <div class="flex-1 flex flex-col justify-center">
        @if ($breakdown)
            <div class="space-y-4">
                @foreach ($breakdown as $domain => $stats)
                    @php
                        $total = $stats['total'] ?? 0;
                        $correct = $stats['correct'] ?? 0;
                        $percent = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
                        $isStrong = $percent >= 70;
                    @endphp
                    <div class="group">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded flex items-center justify-center {{ $isStrong ? 'bg-emerald-500/10' : 'bg-red-500/10' }}">
                                    <x-dynamic-component :component="$isStrong ? 'lucide-trending-up' : 'lucide-trending-down'"
                                        class="w-3 h-3 {{ $isStrong ? 'text-emerald-400' : 'text-red-400' }}" />
                                </div>
                                <span class="text-sm font-semibold text-white capitalize">{{ $domain }}</span>
                            </div>
                            <span class="text-xs font-mono font-bold {{ $isStrong ? 'text-emerald-400' : 'text-red-400' }}">{{ $percent }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-[var(--bg-overlay)] overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 {{ $isStrong ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]' }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
        <div class="text-center py-6">
            <div class="w-16 h-16 rounded-full bg-[var(--bg-overlay)] flex items-center justify-center mx-auto mb-3">
                <x-lucide-clipboard-check class="w-8 h-8 text-[var(--text-muted)] opacity-50" />
            </div>
            <p class="text-sm text-[var(--text-secondary)]">Mavzular bo'yicha tahlil uchun diagnostika testini yeching.</p>
        </div>
        @endif
    </div>
</div>
