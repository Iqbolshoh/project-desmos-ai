@extends('layouts.dashboard')

@section('title', "Solution Summary — Desmos AI")

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Top Action Bar --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center gap-2.5">
                <x-lucide-sparkles class="w-7 h-7 text-[var(--gold)]" />
                Desmos AI Solution Summary
            </h1>
            <p class="text-xs text-[var(--text-secondary)] font-mono mt-0.5">SAT Math step-by-step resolution</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('tutor.index') }}" class="btn-secondary text-sm flex items-center gap-2">
                <x-lucide-plus-circle class="w-4 h-4 text-[var(--teal)]" /> New problem
            </a>
            <a href="{{ route('history.index') }}" class="btn-ghost text-sm flex items-center gap-2">
                <x-lucide-history class="w-4 h-4" /> History
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Left Column: Problem, Steps, Final Answer --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Original Problem Card --}}
            <div class="card p-6 border-l-4 border-l-[var(--teal)] bg-gradient-to-r from-black/40 via-[var(--panel)] to-transparent rounded-2xl shadow-lg">
                <p class="text-xs text-[var(--teal)] font-mono font-bold uppercase tracking-wider mb-2">Identified Problem Statement</p>
                <p class="text-white font-medium text-lg leading-relaxed">{{ $session->input_text }}</p>

                @if($session->input_image_path)
                    <div class="mt-4 pt-3 border-t border-[var(--border-subtle)] flex items-center gap-3">
                        <img src="{{ asset('storage/' . $session->input_image_path) }}" alt="Uploaded image" class="max-h-40 rounded-xl border border-[var(--border-strong)] object-contain shadow-md">
                        <span class="text-xs text-[var(--text-muted)] font-mono">📷 Problem image</span>
                    </div>
                @endif
            </div>

            {{-- Steps List --}}
            <div class="card p-6 border border-[var(--border-strong)] space-y-6 rounded-2xl bg-[var(--panel)] shadow-xl">
                <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
                    <x-lucide-list-ordered class="w-5 h-5 text-[var(--gold)]" /> Step-by-step solution
                </h2>

                @if(isset($session->ai_response['steps']) && count($session->ai_response['steps']) > 0)
                    <div class="relative pl-8 space-y-6 before:absolute before:left-3.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-gradient-to-b before:from-[var(--gold)] via-[var(--teal)] before:to-transparent">
                        @foreach($session->ai_response['steps'] as $step)
                            <div class="relative flex gap-4">
                                <div class="absolute -left-8 w-7 h-7 rounded-full bg-gradient-to-br from-[var(--gold)] to-[var(--gold-hover)] text-black flex items-center justify-center font-extrabold text-xs shadow-md z-10">
                                    {{ $step['stepNumber'] }}
                                </div>
                                <div class="bg-black/30 p-4 rounded-xl border border-[var(--border-subtle)] w-full space-y-2">
                                    <h3 class="text-white font-bold text-base">{{ $step['title'] }}</h3>
                                    <p class="text-[var(--text-secondary)] text-sm leading-relaxed">{{ $step['explanation'] }}</p>
                                    @if(!empty($step['mathExpression']))
                                        <div class="p-3 bg-black/60 rounded-lg border border-[var(--border-strong)] font-mono text-[var(--teal)] text-sm flex items-center justify-between">
                                            <span>{{ $step['mathExpression'] }}</span>
                                            <button type="button" class="copy-step-btn text-xs text-[var(--text-muted)] hover:text-white px-2 py-1 rounded bg-black/40 border border-[var(--border-subtle)]" onclick="navigator.clipboard.writeText('{{ addslashes($step['mathExpression']) }}'); this.textContent = 'Copied ✓'; setTimeout(() => this.textContent = 'Copy', 1500);">
                                                Copy
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-muted)] italic">No steps available.</p>
                @endif
            </div>

            {{-- Final Answer Box --}}
            <div class="card p-6 border border-[var(--gold-border)] bg-gradient-to-br from-[var(--gold-soft)] via-black/40 to-transparent relative overflow-hidden rounded-2xl shadow-xl">
                <div class="absolute -top-4 -right-4 p-4 opacity-10 pointer-events-none">
                    <x-lucide-check-circle class="w-32 h-32 text-[var(--gold)]" />
                </div>
                <h2 class="text-xs font-mono font-bold uppercase tracking-wider text-[var(--gold)] mb-2">Final Answer</h2>
                <div class="text-2xl text-white font-extrabold font-mono bg-black/60 px-5 py-3 rounded-xl border border-[var(--gold-border)] inline-block shadow-inner mb-4">
                    {{ $session->ai_response['finalAnswer'] ?? 'Not found' }}
                </div>
                @if(!empty($session->ai_response['explanation']))
                    <div class="text-sm text-[var(--text-secondary)] leading-relaxed space-y-1 bg-black/30 p-4 rounded-xl border border-[var(--border-subtle)]">
                        {!! nl2br(e($session->ai_response['explanation'])) !!}
                    </div>
                @endif
            </div>

        </div>

        {{-- Right Column: Desmos Interactive Graph & Actions --}}
        <div class="lg:col-span-2 space-y-6">

            @php $graphExpr = $session->desmos_state['expression'] ?? null; @endphp
            
            {{-- Desmos Calculator Card --}}
            <div class="card p-4 border border-[var(--border-strong)] bg-[var(--panel)] shadow-xl rounded-2xl space-y-3">
                <div class="flex justify-between items-center pb-2 border-b border-[var(--border-subtle)]">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <x-lucide-line-chart class="w-4 h-4 text-[var(--teal)]" /> Interactive Desmos Graph
                    </h3>
                    @if($graphExpr)
                    <form action="{{ route('history.save-graph') }}" method="POST">
                        @csrf
                        <input type="hidden" name="title" value="{{ Str::limit($session->input_text, 50) }}">
                        <input type="hidden" name="expression" value="{{ $graphExpr }}">
                        <button type="submit" class="text-xs font-medium text-[var(--teal)] hover:text-white flex items-center gap-1 bg-black/40 px-2.5 py-1 rounded-lg border border-[var(--border-subtle)] hover:border-[var(--teal)] transition-all" title="Save graph">
                            <x-lucide-bookmark class="w-3.5 h-3.5" /> Save
                        </button>
                    </form>
                    @endif
                </div>

                @if($graphExpr)
                    <x-desmos-calculator id="tutor-graph" height="340px" :expression="$graphExpr" />
                    
                    <div class="bg-black/40 p-3 rounded-xl border border-[var(--border-subtle)] space-y-1.5">
                        <span class="text-xs font-mono text-[var(--text-muted)] block">Desmos expression:</span>
                        <div class="flex items-center justify-between font-mono text-sm text-[var(--gold)]">
                            <code>{{ $graphExpr }}</code>
                            <button type="button" class="text-xs text-[var(--text-secondary)] hover:text-white bg-black/60 px-2 py-1 rounded border border-[var(--border-subtle)]" onclick="navigator.clipboard.writeText('{{ addslashes($graphExpr) }}'); this.textContent = 'Copied ✓'; setTimeout(() => this.textContent = 'Copy', 1500);">
                                Copy
                            </button>
                        </div>
                    </div>
                @else
                    <div class="p-8 text-center text-sm text-[var(--text-muted)] bg-black/20 rounded-xl border border-[var(--border-subtle)]">
                        <x-lucide-info class="w-8 h-8 text-[var(--text-muted)] mx-auto mb-2 opacity-50" />
                        This problem doesn't require a separate graph.
                    </div>
                @endif

                <a href="https://www.desmos.com/calculator" target="_blank" rel="noopener" class="text-xs text-[var(--teal)] hover:underline flex items-center gap-1 pt-1 font-semibold">
                    Open on Desmos.com ↗
                </a>
            </div>

            {{-- AI Follow-up Assistance --}}
            <div class="card p-5 border border-[var(--border-subtle)] bg-gradient-to-br from-[var(--bg-overlay)] to-black/40 rounded-2xl shadow-lg space-y-3">
                <h3 class="font-bold text-white flex items-center gap-2 text-sm">
                    <x-lucide-message-square-plus class="w-4 h-4 text-[var(--gold)]" />
                    Have a question?
                </h3>
                <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                    Need more explanation about the solution? Chat directly with the AI Chat tutor.
                </p>
                <div class="space-y-2 pt-1">
                    <a href="{{ route('chat.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-black/30 border border-[var(--border-subtle)] hover:border-[var(--teal)] text-xs text-[var(--text-secondary)] hover:text-white transition-all">
                        <span>"Why was this method used?"</span>
                        <x-lucide-arrow-right class="w-4 h-4 text-[var(--teal)]" />
                    </a>
                    <a href="{{ route('chat.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-black/30 border border-[var(--border-subtle)] hover:border-[var(--teal)] text-xs text-[var(--text-secondary)] hover:text-white transition-all">
                        <span>"Give me a similar exercise"</span>
                        <x-lucide-arrow-right class="w-4 h-4 text-[var(--gold)]" />
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
