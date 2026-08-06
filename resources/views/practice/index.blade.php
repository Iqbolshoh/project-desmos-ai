@extends('layouts.dashboard')

@section('title', 'SAT Math Practice Bank')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)]">
                    <x-lucide-dumbbell class="w-6 h-6 text-[var(--accent)]" />
                </div>
                Practice Bank
            </h1>
            <p class="text-[var(--text-secondary)] mt-2">Choose a SAT Math topic to practice targeted questions and sharpen your skills.</p>
        </div>
        <div class="hidden md:flex items-center gap-2 text-sm text-[var(--text-muted)] font-mono">
            <x-lucide-layers class="w-4 h-4" />
            {{ $topics->count() }} Topics
        </div>
    </div>

    {{-- Topic cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @php
        $accentColors = [
            ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400', 'glow' => 'shadow-blue-500/20'],
            ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400', 'glow' => 'shadow-purple-500/20'],
            ['bg' => 'bg-emerald-500/10', 'border' => 'border-emerald-500/20', 'text' => 'text-emerald-400', 'glow' => 'shadow-emerald-500/20'],
            ['bg' => 'bg-orange-500/10', 'border' => 'border-orange-500/20', 'text' => 'text-orange-400', 'glow' => 'shadow-orange-500/20'],
            ['bg' => 'bg-pink-500/10', 'border' => 'border-pink-500/20', 'text' => 'text-pink-400', 'glow' => 'shadow-pink-500/20'],
            ['bg' => 'bg-cyan-500/10', 'border' => 'border-cyan-500/20', 'text' => 'text-cyan-400', 'glow' => 'shadow-cyan-500/20'],
            ['bg' => 'bg-yellow-500/10', 'border' => 'border-yellow-500/20', 'text' => 'text-yellow-400', 'glow' => 'shadow-yellow-500/20'],
        ];
        @endphp
        @foreach($topics as $i => $topic)
            @php $c = $accentColors[$i % count($accentColors)]; @endphp
            <a href="{{ route('practice.topic', $topic->slug) }}"
               class="card p-6 group block relative overflow-hidden h-[180px] flex flex-col justify-between hover:border-white/20 hover:-translate-y-1 transition-all duration-300 rounded-2xl shadow-xl">

                <div class="absolute inset-0 bg-gradient-to-br from-white/[0.02] to-transparent group-hover:from-white/[0.05] transition-all duration-300"></div>

                <div class="absolute -bottom-4 -right-4 opacity-[0.07] group-hover:opacity-[0.12] group-hover:scale-110 transition-all duration-500">
                    <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-28 h-28" />
                </div>

                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} {{ $c['border'] }} border flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg {{ $c['glow'] }}">
                        <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-6 h-6 {{ $c['text'] }}" />
                    </div>
                    <h3 class="font-bold text-white text-lg">{{ $topic->name }}</h3>
                    <p class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-widest mt-1 font-mono">{{ $topic->domain }}</p>
                </div>

                <div class="relative z-10 flex items-center justify-between">
                    <span class="text-xs text-[var(--text-muted)] flex items-center gap-1 font-medium">
                        <x-lucide-book-open class="w-3.5 h-3.5 text-[var(--gold)]" /> Start Practice
                    </span>
                    <div class="w-8 h-8 rounded-lg {{ $c['bg'] }} {{ $c['border'] }} border flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 translate-x-2 group-hover:translate-x-0">
                        <x-lucide-arrow-right class="w-4 h-4 {{ $c['text'] }}" />
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
