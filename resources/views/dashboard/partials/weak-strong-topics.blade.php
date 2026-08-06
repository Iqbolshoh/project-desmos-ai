<div class="card p-6 border-[var(--border-strong)] rounded-2xl shadow-xl space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-white flex items-center gap-2 text-base">
            <x-lucide-bar-chart-3 class="w-5 h-5 text-[var(--accent)]" />
            Topic Mastery Breakdown
        </h3>
        <a href="{{ route('diagnostic.start') }}" class="text-xs text-[var(--gold)] hover:underline font-medium">Retake Test →</a>
    </div>

    @if($latestDiagnostic && !empty($latestDiagnostic->breakdown))
        <div class="space-y-3">
            @foreach($latestDiagnostic->breakdown as $domain => $stats)
                @if($stats['total'] > 0)
                    @php
                        $percent = round(($stats['correct'] / $stats['total']) * 100);
                        $color = $percent > 75 ? 'emerald' : ($percent > 50 ? 'amber' : 'red');
                    @endphp
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-white font-semibold capitalize">{{ str_replace('-', ' ', $domain) }}</span>
                            <span class="font-mono text-{{ $color }}-400 font-bold">{{ $percent }}%</span>
                        </div>
                        <div class="w-full bg-black/50 rounded-full h-2 border border-[var(--border-subtle)] overflow-hidden">
                            <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-xs text-[var(--text-muted)] border border-dashed border-[var(--border-subtle)] rounded-xl">
            No diagnostic breakdown available. Take the diagnostic test to see your weak & strong topics!
        </div>
    @endif
</div>
