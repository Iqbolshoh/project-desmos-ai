@extends('layouts.dashboard')

@section('title', 'History & Saved Graphs')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)]">
                    <x-lucide-history class="w-6 h-6 text-[var(--accent)]" />
                </div>
                History & Saved Graphs
            </h1>
            <p class="text-[var(--text-secondary)] mt-2">Review your past SAT AI tutor sessions and saved Desmos expressions.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl flex items-center gap-3">
        <x-lucide-check-circle class="w-5 h-5 flex-shrink-0" />
        {{ session('success') }}
    </div>
    @endif

    <div x-data="{ tab: 'sessions' }">

        {{-- Tabs --}}
        <div class="flex gap-1 p-1 bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-xl mb-6 w-fit">
            <button @click="tab = 'sessions'"
                :class="tab === 'sessions' ? 'bg-[var(--bg-surface)] text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-secondary)]'"
                class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all duration-200">
                <span class="flex items-center gap-2">
                    <x-lucide-bot class="w-4 h-4" /> AI Tutor Sessions ({{ $sessions->total() }})
                </span>
            </button>
            <button @click="tab = 'graphs'"
                :class="tab === 'graphs' ? 'bg-[var(--bg-surface)] text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-secondary)]'"
                class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all duration-200">
                <span class="flex items-center gap-2">
                    <x-lucide-line-chart class="w-4 h-4" /> Saved Graphs ({{ $savedGraphs->total() }})
                </span>
            </button>
        </div>

        {{-- Sessions Tab --}}
        <div x-show="tab === 'sessions'" class="space-y-4">
            @if($sessions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($sessions as $session)
                    <a href="{{ route('tutor.show', $session->id) }}" class="card p-5 group block hover:-translate-y-1 transition-all duration-300 hover:border-[var(--accent-border)] rounded-2xl">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-mono text-[var(--text-muted)] group-hover:text-[var(--accent-hover)] transition-colors">
                                {{ $session->created_at->format('M d, Y H:i') }}
                            </span>
                            @if($session->input_image_path)
                                <div class="w-6 h-6 rounded-md bg-[var(--accent-soft)] flex items-center justify-center">
                                    <x-lucide-image class="w-3.5 h-3.5 text-[var(--accent)]" />
                                </div>
                            @endif
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center mb-3">
                            <x-lucide-bot class="w-4 h-4 text-white" />
                        </div>
                        <h3 class="font-bold text-white mb-2 line-clamp-2 leading-snug">
                            {{ $session->input_text ?? 'Problem prompt...' }}
                        </h3>
                        <p class="text-sm text-[var(--text-secondary)] line-clamp-1">
                            Answer: {{ $session->ai_response['finalAnswer'] ?? '...' }}
                        </p>
                        <div class="mt-3 flex items-center gap-1.5 text-xs text-[var(--accent-hover)] opacity-0 group-hover:opacity-100 transition-opacity">
                            View Details <x-lucide-arrow-right class="w-3 h-3" />
                        </div>
                    </a>
                    @endforeach
                </div>
                <div class="mt-6">{{ $sessions->appends(['graphs_page' => request('graphs_page')])->links() }}</div>
            @else
                <div class="text-center py-16 card border-dashed border-[var(--border-strong)] rounded-2xl">
                    <x-lucide-history class="w-16 h-16 text-[var(--text-muted)] mx-auto mb-4 opacity-40" />
                    <h3 class="text-xl font-bold text-white">No Tutor Sessions Yet</h3>
                    <p class="text-[var(--text-secondary)] mt-2 mb-6">You haven't solved any SAT math problems with AI Tutor yet.</p>
                    <a href="{{ route('tutor.index') }}" class="btn-primary">Solve Your First Problem</a>
                </div>
            @endif
        </div>

        {{-- Saved Graphs Tab --}}
        <div x-show="tab === 'graphs'" style="display: none;" class="space-y-4">
            @if($savedGraphs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($savedGraphs as $graph)
                    <div class="card overflow-hidden border border-[var(--border-strong)] bg-black flex flex-col h-[350px] rounded-2xl">
                        <div class="p-3 bg-[var(--bg-raised)] border-b border-[var(--border-subtle)] flex justify-between items-center shrink-0">
                            <h3 class="font-bold text-white text-sm line-clamp-1 flex-1 mr-4">{{ $graph->title }}</h3>
                            <span class="text-xs text-[var(--text-muted)] shrink-0">{{ $graph->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex-1 min-h-0 relative">
                            <x-desmos-calculator
                                :id="'graph-'.$graph->id"
                                height="100%"
                                :expression="$graph->desmos_state['expression'] ?? ''"
                            />
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $savedGraphs->appends(['sessions_page' => request('sessions_page')])->links() }}</div>
            @else
                <div class="text-center py-16 card border-dashed border-[var(--border-strong)] rounded-2xl">
                    <x-lucide-line-chart class="w-16 h-16 text-[var(--text-muted)] mx-auto mb-4 opacity-40" />
                    <h3 class="text-xl font-bold text-white">No Saved Graphs</h3>
                    <p class="text-[var(--text-secondary)] mt-2">You can bookmark interactive Desmos graphs while solving problems.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
