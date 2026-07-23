@extends('layouts.dashboard')

@section('title', 'Roadmap')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-map class="w-7 h-7 text-[var(--accent)]" />
            Shaxsiy O'quv Rejasi (Roadmap)
        </h1>
        
        <form action="{{ route('roadmap.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn-secondary text-sm">
                <x-lucide-refresh-cw class="w-4 h-4" /> Qayta tuzish
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-lg flex items-center gap-3">
        <x-lucide-check-circle class="w-5 h-5" />
        {{ session('success') }}
    </div>
    @endif

    @if($roadmap)
        <!-- Roadmap Header -->
        <div class="card p-6 border-[var(--border-strong)] bg-gradient-to-r from-[var(--bg-raised)] to-[var(--bg-surface)]">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">{{ $roadmap->title }}</h2>
                    <p class="text-[var(--text-secondary)] text-sm">{{ $roadmap->description }}</p>
                </div>
                <div class="text-right w-full md:w-auto">
                    <div class="text-sm text-[var(--text-muted)] mb-1">Bajarilish holati</div>
                    <div class="flex items-center gap-3">
                        <div class="w-full md:w-48 bg-black/50 rounded-full h-3 border border-[var(--border-subtle)] overflow-hidden">
                            <div class="bg-[var(--accent)] h-3 rounded-full transition-all duration-500" style="width: {{ $roadmap->progress_percent }}%"></div>
                        </div>
                        <span class="text-white font-bold font-mono">{{ $roadmap->progress_percent }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roadmap Stages -->
        <div class="relative pl-4 md:pl-8 space-y-8 before:absolute before:inset-0 before:ml-[1.65rem] md:before:ml-[2.65rem] before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-[var(--accent)] before:via-[var(--border-strong)] before:to-transparent mt-8">
            
            @foreach($roadmap->stages as $stage)
            <div class="relative flex items-start justify-between gap-6">
                <!-- Timeline dot -->
                <div class="absolute left-0 md:left-2 w-6 h-6 rounded-full {{ $stage['completed'] ? 'bg-[var(--accent)] text-white' : 'bg-[var(--bg-overlay)] border-2 border-[var(--border-strong)] text-[var(--text-muted)]' }} shadow flex items-center justify-center -translate-x-[0.4rem] mt-1.5 z-10 transition-colors">
                    @if($stage['completed'])
                        <x-lucide-check class="w-4 h-4" />
                    @else
                        <div class="w-2 h-2 rounded-full bg-current"></div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="ml-8 md:ml-12 w-full card p-5 border-[var(--border-strong)] {{ $stage['completed'] ? 'opacity-75' : '' }} hover:border-[var(--accent-soft)] transition-colors">
                    <h3 class="text-lg font-bold text-white mb-4">{{ $stage['title'] }}</h3>
                    
                    <div class="space-y-2">
                        @foreach($stage['tasks'] as $task)
                        <form action="{{ route('roadmap.toggle', $roadmap->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                            <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-lg border {{ $task['completed'] ? 'bg-green-500/5 border-green-500/20' : 'bg-black/20 border-[var(--border-subtle)] hover:bg-[var(--bg-overlay)]' }} transition-colors text-left cursor-pointer group">
                                <div class="w-5 h-5 rounded flex items-center justify-center border {{ $task['completed'] ? 'bg-green-500 border-green-500 text-white' : 'border-[var(--border-strong)] group-hover:border-[var(--accent)] text-transparent' }}">
                                    <x-lucide-check class="w-3.5 h-3.5" />
                                </div>
                                <span class="{{ $task['completed'] ? 'text-[var(--text-muted)] line-through' : 'text-white' }} text-sm font-medium">
                                    {{ $task['title'] }}
                                </span>
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    @else
        <div class="text-center py-16 card border-dashed border-[var(--border-strong)]">
            <x-lucide-map class="w-16 h-16 text-[var(--text-muted)] mx-auto mb-4 opacity-50" />
            <h3 class="text-xl font-bold text-white mb-2">Sizda o'quv rejasi yo'q</h3>
            <p class="text-[var(--text-secondary)] mb-6 max-w-md mx-auto">Tuzilgan reja ko'nikmalaringizni to'g'ri shakllantirishga yordam beradi. Buning uchun avval diagnostika testidan o'ting.</p>
            <a href="{{ route('diagnostic.start') }}" class="btn-primary shadow-lg shadow-[var(--accent-glow)]">
                Diagnostika testini boshlash
            </a>
        </div>
    @endif
</div>
@endsection
