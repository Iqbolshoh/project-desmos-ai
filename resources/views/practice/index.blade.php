@extends('layouts.dashboard')

@section('title', 'Mashg\'ulot')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <x-lucide-dumbbell class="w-7 h-7 text-[var(--accent)]" />
                Mashg'ulotlar (Practice)
            </h1>
            <p class="text-[var(--text-secondary)] mt-1">O'zingizni sinab ko'rish uchun mavzuni tanlang.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($topics as $topic)
        <a href="{{ route('practice.topic', $topic->slug) }}" class="card p-6 card-hover group block relative overflow-hidden h-[160px] flex flex-col justify-between">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 group-hover:scale-110 transition-all duration-300">
                <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-24 h-24 text-[var(--accent)]" />
            </div>
            
            <div class="relative z-10">
                <div class="w-10 h-10 rounded-lg bg-[var(--accent-soft)] flex items-center justify-center text-[var(--accent-hover)] mb-4 shadow-inner border border-[var(--accent-border)]">
                    <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-5 h-5" />
                </div>
                <h3 class="font-bold text-white text-lg line-clamp-1">{{ $topic->name }}</h3>
                <p class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mt-1">{{ $topic->domain }}</p>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
