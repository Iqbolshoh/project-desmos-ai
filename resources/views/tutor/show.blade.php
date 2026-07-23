@extends('layouts.dashboard')

@section('title', 'Ye\'chim')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-sparkles class="w-7 h-7 text-[var(--gold)]" />
            Ye'chim xulosasi
        </h1>
        <a href="{{ route('tutor.index') }}" class="btn-secondary text-sm">
            <x-lucide-arrow-left class="w-4 h-4" /> Orqaga
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        
        <!-- Left: Steps & Answer -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Original Question -->
            <div class="card p-5 border-l-4 border-l-[var(--accent)] bg-gradient-to-r from-[var(--accent-soft)] to-transparent">
                <p class="text-sm text-[var(--accent-hover)] font-bold mb-1 uppercase tracking-wide">Sizning savolingiz</p>
                <p class="text-white font-medium text-lg">{{ $session->question_text }}</p>
                
                @if($session->image_path)
                    <div class="mt-4">
                        <img src="{{ asset('storage/' . $session->image_path) }}" alt="Yuklangan rasm" class="max-h-48 rounded-lg border border-[var(--border-strong)]">
                    </div>
                @endif
            </div>

            <!-- Steps -->
            <div class="card p-6 border border-[var(--border-strong)] space-y-5">
                <h2 class="text-xl font-bold text-white">Qadam-baqadam yechim</h2>
                
                @if(isset($session->response_data['steps']) && count($session->response_data['steps']) > 0)
                    <div class="space-y-4">
                        @foreach($session->response_data['steps'] as $step)
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--accent-soft)] text-[var(--accent-hover)] flex items-center justify-center font-bold text-sm border border-[var(--accent-border)]">
                                    {{ $step['stepNumber'] }}
                                </div>
                                <div>
                                    <h3 class="text-white font-bold">{{ $step['title'] }}</h3>
                                    <p class="text-[var(--text-secondary)] mt-1 text-sm">{{ $step['explanation'] }}</p>
                                    @if(!empty($step['mathExpression']))
                                        <div class="mt-2 p-2 bg-black/30 rounded border border-[var(--border-subtle)] font-mono text-[var(--accent-hover)] text-sm">
                                            {{ $step['mathExpression'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-muted)] italic">Qadamlar mavjud emas.</p>
                @endif
            </div>
            
            <!-- Final Answer -->
            <div class="card p-6 border border-[var(--gold-border)] bg-gradient-to-b from-[var(--gold-soft)] to-transparent relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <x-lucide-check-circle class="w-24 h-24 text-[var(--gold)]" />
                </div>
                <h2 class="text-lg font-bold text-[var(--gold)] mb-2 relative z-10">Yakuniy javob</h2>
                <div class="text-2xl text-white font-bold font-mono bg-black/40 p-4 rounded-xl border border-[var(--gold-border)] relative z-10 inline-block shadow-lg">
                    {{ $session->response_data['finalAnswer'] ?? 'Topilmadi' }}
                </div>
                <p class="mt-4 text-[var(--text-secondary)] relative z-10 leading-relaxed">
                    {{ $session->response_data['explanation'] ?? '' }}
                </p>
            </div>
        </div>
        
        <!-- Right: Graph & Actions -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Desmos Graph -->
            @if(isset($session->response_data['graphExpression']))
            <div class="card p-1 border border-[var(--border-strong)] bg-black shadow-xl">
                <div class="p-3 pb-2 flex justify-between items-center border-b border-[var(--border-subtle)] bg-[var(--bg-raised)] rounded-t-lg">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <x-lucide-line-chart class="w-4 h-4 text-[var(--accent-hover)]" /> Grafik
                    </h3>
                    
                    <form action="{{ route('history.save-graph') }}" method="POST">
                        @csrf
                        <input type="hidden" name="title" value="{{ Str::limit($session->question_text, 50) }}">
                        <input type="hidden" name="expression" value="{{ $session->response_data['graphExpression'] }}">
                        <button type="submit" class="text-xs btn-ghost py-1 px-2 rounded hover:bg-[var(--accent-soft)] hover:text-[var(--accent-hover)]" title="Grafikni tarixga saqlash">
                            <x-lucide-save class="w-4 h-4" />
                        </button>
                    </form>
                </div>
                
                <x-desmos-calculator 
                    id="tutor-graph" 
                    height="300px" 
                    :expression="$session->response_data['graphExpression']" 
                />
            </div>
            @endif
            
            <!-- Quick Help -->
            <div class="card p-5 border border-[var(--border-subtle)] bg-[var(--bg-overlay)]">
                <h3 class="font-bold text-white mb-3 flex items-center gap-2 text-sm">
                    <x-lucide-message-circle-question class="w-4 h-4 text-[var(--accent)]" /> 
                    Tushunmadingizmi?
                </h3>
                <div class="space-y-2">
                    <button class="w-full text-left p-3 rounded bg-black/20 border border-[var(--border-subtle)] hover:border-[var(--accent)] hover:bg-[var(--accent-soft)] text-sm text-[var(--text-secondary)] hover:text-white transition-colors">
                        "Nega bunday bo'lganini tushuntirib bering"
                    </button>
                    <button class="w-full text-left p-3 rounded bg-black/20 border border-[var(--border-subtle)] hover:border-[var(--accent)] hover:bg-[var(--accent-soft)] text-sm text-[var(--text-secondary)] hover:text-white transition-colors">
                        "Xuddi shunga o'xshash bitta masala bering"
                    </button>
                </div>
                <p class="text-xs text-[var(--text-muted)] mt-4 italic text-center">Chat funksiyasi Phase 4 da qo'shiladi</p>
            </div>
            
        </div>
    </div>
</div>
@endsection
