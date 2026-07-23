@extends('layouts.dashboard')

@section('title', 'Tarix')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <x-lucide-history class="w-7 h-7 text-[var(--accent)]" />
                Tarix va Saqlanganlar
            </h1>
            <p class="text-[var(--text-secondary)] mt-1">Oldingi masalalar va saqlangan grafiklarni ko'rib chiqing.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-lg flex items-center gap-3">
        <x-lucide-check-circle class="w-5 h-5" />
        {{ session('success') }}
    </div>
    @endif

    <div x-data="{ tab: 'sessions' }">
        
        <!-- Tabs -->
        <div class="flex gap-4 border-b border-[var(--border-strong)] mb-6">
            <button @click="tab = 'sessions'" :class="tab === 'sessions' ? 'border-[var(--accent)] text-white' : 'border-transparent text-[var(--text-muted)] hover:text-[var(--text-secondary)]'" class="pb-3 px-2 border-b-2 font-semibold text-sm transition-colors">
                Tutor Tarixi ({{ $sessions->total() }})
            </button>
            <button @click="tab = 'graphs'" :class="tab === 'graphs' ? 'border-[var(--accent)] text-white' : 'border-transparent text-[var(--text-muted)] hover:text-[var(--text-secondary)]'" class="pb-3 px-2 border-b-2 font-semibold text-sm transition-colors">
                Saqlangan Grafiklar ({{ $savedGraphs->total() }})
            </button>
        </div>
        
        <!-- Sessions Tab -->
        <div x-show="tab === 'sessions'" class="space-y-4">
            @if($sessions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($sessions as $session)
                    <a href="{{ route('tutor.show', $session->id) }}" class="card p-5 card-hover block group">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-mono text-[var(--text-muted)] group-hover:text-[var(--accent-hover)] transition-colors">
                                {{ $session->created_at->format('d M, Y H:i') }}
                            </span>
                            @if($session->image_path)
                                <x-lucide-image class="w-4 h-4 text-[var(--text-muted)]" />
                            @endif
                        </div>
                        <h3 class="font-bold text-white mb-2 line-clamp-2 leading-snug">
                            {{ $session->question_text }}
                        </h3>
                        <p class="text-sm text-[var(--text-secondary)] line-clamp-1">
                            Javob: {{ $session->response_data['finalAnswer'] ?? '...' }}
                        </p>
                    </a>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $sessions->links('pagination::tailwind') }}
                </div>
            @else
                <div class="text-center py-12 bg-[var(--bg-overlay)] rounded-xl border border-dashed border-[var(--border-strong)]">
                    <x-lucide-history class="w-12 h-12 text-[var(--text-muted)] mx-auto mb-3 opacity-50" />
                    <h3 class="text-lg font-bold text-white">Tarix bo'sh</h3>
                    <p class="text-[var(--text-secondary)] mt-1">Hali hech qanday masala yechmagansiz.</p>
                    <a href="{{ route('tutor.index') }}" class="btn-primary mt-4">Birinchi masalani yechish</a>
                </div>
            @endif
        </div>
        
        <!-- Saved Graphs Tab -->
        <div x-show="tab === 'graphs'" style="display: none;" class="space-y-4">
            @if($savedGraphs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($savedGraphs as $graph)
                    <div class="card overflow-hidden border border-[var(--border-strong)] bg-black flex flex-col h-[350px]">
                        <div class="p-3 bg-[var(--bg-raised)] border-b border-[var(--border-subtle)] flex justify-between items-center shrink-0">
                            <h3 class="font-bold text-white text-sm line-clamp-1 flex-1 mr-4">
                                {{ $graph->title }}
                            </h3>
                            <span class="text-xs text-[var(--text-muted)] shrink-0">
                                {{ $graph->created_at->format('d M, Y') }}
                            </span>
                        </div>
                        <div class="flex-1 min-h-0 relative">
                            <div class="absolute inset-0 pointer-events-none z-10 hidden" style="box-shadow: inset 0 0 0 1px var(--border-subtle)"></div>
                            <x-desmos-calculator 
                                :id="'graph-'.$graph->id"
                                height="100%"
                                :expression="$graph->state_data['expression'] ?? ''"
                            />
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $savedGraphs->links('pagination::tailwind') }}
                </div>
            @else
                <div class="text-center py-12 bg-[var(--bg-overlay)] rounded-xl border border-dashed border-[var(--border-strong)]">
                    <x-lucide-line-chart class="w-12 h-12 text-[var(--text-muted)] mx-auto mb-3 opacity-50" />
                    <h3 class="text-lg font-bold text-white">Saqlangan grafiklar yo'q</h3>
                    <p class="text-[var(--text-secondary)] mt-1">Masala yechish davomida qiziqarli grafiklarni saqlab qolishingiz mumkin.</p>
                </div>
            @endif
        </div>
        
    </div>

</div>
@endsection
