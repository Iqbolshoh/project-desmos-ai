@extends('layouts.dashboard')

@section('title', 'Natijalar')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="text-center py-8">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-500/10 border-4 border-green-500/20 mb-4">
            <x-lucide-award class="w-10 h-10 text-green-400" />
        </div>
        <h1 class="text-3xl font-extrabold text-white">Tabriklaymiz, test yakunlandi!</h1>
        <p class="text-[var(--text-secondary)] mt-2">Sizning joriy bilim darajangiz tahlil qilindi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Overall Score -->
        <div class="card p-6 border-[var(--gold-border)] bg-gradient-to-b from-[var(--gold-soft)] to-black text-center relative overflow-hidden md:col-span-1">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <x-lucide-bar-chart-2 class="w-24 h-24 text-[var(--gold)]" />
            </div>
            <h3 class="text-[var(--gold)] font-bold text-sm uppercase tracking-wider mb-2 relative z-10">Taxminiy SAT Ballingiz</h3>
            <div class="text-6xl font-black text-white my-4 relative z-10 font-mono drop-shadow-lg">
                {{ $result->overall_score_estimate }}<span class="text-2xl text-[var(--text-muted)]">/800</span>
            </div>
            <p class="text-sm text-white/70 relative z-10">Jami {{ $result->total_questions }} ta savoldan {{ $result->correct_count }} tasiga to'g'ri javob berdingiz.</p>
        </div>

        <!-- Breakdown -->
        <div class="card p-6 border-[var(--border-strong)] md:col-span-2 flex flex-col justify-center">
            <h3 class="text-white font-bold text-lg mb-4 flex items-center gap-2">
                <x-lucide-pie-chart class="w-5 h-5 text-[var(--accent)]" /> 
                Yo'nalishlar bo'yicha tahlil
            </h3>
            
            <div class="space-y-4">
                @foreach($result->breakdown as $domain => $stats)
                    @if($stats['total'] > 0)
                        @php
                            $percent = round(($stats['correct'] / $stats['total']) * 100);
                            $colorClass = $percent > 75 ? 'bg-green-500' : ($percent > 50 ? 'bg-yellow-500' : 'bg-red-500');
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-white font-medium capitalize">{{ str_replace('-', ' ', $domain) }}</span>
                                <span class="text-[var(--text-secondary)]">{{ $stats['correct'] }}/{{ $stats['total'] }} ({{ $percent }}%)</span>
                            </div>
                            <div class="w-full bg-black/50 rounded-full h-2.5 border border-[var(--border-subtle)] overflow-hidden">
                                <div class="{{ $colorClass }} h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Weakness & Next Steps -->
    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] flex flex-col md:flex-row gap-6 items-center">
        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center">
            <x-lucide-alert-triangle class="w-8 h-8 text-red-400" />
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="text-white font-bold text-lg">Asosiy e'tibor</h3>
            <p class="text-[var(--text-secondary)] mt-1">{{ $result->weakness_summary }}</p>
        </div>
        <div class="flex-shrink-0 w-full md:w-auto mt-4 md:mt-0">
            <a href="{{ route('roadmap.show') }}" class="btn-primary w-full shadow-lg shadow-[var(--accent-glow)]">
                Shaxsiy rejamni ko'rish <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>
</div>
@endsection
