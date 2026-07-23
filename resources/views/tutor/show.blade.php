@extends('layouts.dashboard')

@section('title', "Ye'chim")

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

        {{-- Left: Steps & Answer --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Original Question --}}
            <div class="card p-5 border-l-4 border-l-[var(--accent)] bg-gradient-to-r from-[var(--accent-soft)] to-transparent rounded-2xl">
                <p class="text-sm text-[var(--accent-hover)] font-bold mb-1 uppercase tracking-wide">Sizning savolingiz</p>
                <p class="text-white font-medium text-lg">{{ $session->input_text }}</p>

                @if($session->input_image_path)
                    <div class="mt-4">
                        <img src="{{ asset('storage/' . $session->input_image_path) }}" alt="Yuklangan rasm" class="max-h-48 rounded-lg border border-[var(--border-strong)]">
                    </div>
                @endif
            </div>

            {{-- Steps --}}
            <div class="card p-6 border border-[var(--border-strong)] space-y-5 rounded-2xl">
                <h2 class="text-xl font-bold text-white">Qadam-baqadam yechim</h2>

                @if(isset($session->ai_response['steps']) && count($session->ai_response['steps']) > 0)
                    <div class="relative pl-8 space-y-6 before:absolute before:left-3 before:top-0 before:bottom-0 before:w-0.5 before:bg-gradient-to-b before:from-[var(--accent)] before:to-transparent">
                        @foreach($session->ai_response['steps'] as $step)
                            <div class="relative flex gap-4">
                                <div class="absolute -left-8 w-6 h-6 rounded-full bg-[var(--accent-soft)] text-[var(--accent-hover)] flex items-center justify-center font-bold text-xs border border-[var(--accent-border)] z-10">
                                    {{ $step['stepNumber'] }}
                                </div>
                                <div>
                                    <h3 class="text-white font-bold">{{ $step['title'] }}</h3>
                                    <p class="text-[var(--text-secondary)] mt-1 text-sm leading-relaxed">{{ $step['explanation'] }}</p>
                                    @if(!empty($step['mathExpression']))
                                        <div class="mt-2 p-3 bg-black/40 rounded-xl border border-[var(--border-subtle)] font-mono text-[var(--accent-hover)] text-sm">
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

            {{-- Final Answer --}}
            <div class="card p-6 border border-[var(--gold-border)] bg-gradient-to-b from-[var(--gold-soft)] to-transparent relative overflow-hidden rounded-2xl">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <x-lucide-check-circle class="w-24 h-24 text-[var(--gold)]" />
                </div>
                <h2 class="text-lg font-bold text-[var(--gold)] mb-3 relative z-10">Yakuniy javob</h2>
                <div class="text-2xl text-white font-bold font-mono bg-black/40 p-4 rounded-xl border border-[var(--gold-border)] relative z-10 inline-block shadow-lg">
                    {{ $session->ai_response['finalAnswer'] ?? 'Topilmadi' }}
                </div>
                <p class="mt-4 text-[var(--text-secondary)] relative z-10 leading-relaxed">
                    {{ $session->ai_response['explanation'] ?? '' }}
                </p>
            </div>
        </div>

        {{-- Right: Graph & Actions --}}
        <div class="lg:col-span-2 space-y-6">

            @php $graphExpr = $session->desmos_state['expression'] ?? null; @endphp
            @if($graphExpr)
            <div class="card p-1 border border-[var(--border-strong)] bg-black shadow-xl rounded-2xl">
                <div class="p-3 pb-2 flex justify-between items-center border-b border-[var(--border-subtle)] bg-[var(--bg-raised)] rounded-t-xl">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <x-lucide-line-chart class="w-4 h-4 text-[var(--accent-hover)]" /> Grafik
                    </h3>
                    <form action="{{ route('history.save-graph') }}" method="POST">
                        @csrf
                        <input type="hidden" name="title" value="{{ Str::limit($session->input_text, 50) }}">
                        <input type="hidden" name="expression" value="{{ $graphExpr }}">
                        <button type="submit" class="text-xs btn-ghost py-1 px-2 rounded hover:bg-[var(--accent-soft)] hover:text-[var(--accent-hover)]" title="Grafikni tarixga saqlash">
                            <x-lucide-save class="w-4 h-4" />
                        </button>
                    </form>
                </div>
                <x-desmos-calculator id="tutor-graph" height="300px" :expression="$graphExpr" />
            </div>
            @endif

            {{-- Quick Help --}}
            <div class="card p-5 border border-[var(--border-subtle)] bg-[var(--bg-overlay)] rounded-2xl">
                <h3 class="font-bold text-white mb-3 flex items-center gap-2 text-sm">
                    <x-lucide-message-circle-question class="w-4 h-4 text-[var(--accent)]" />
                    Tushunmadingizmi?
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('chat.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-black/20 border border-[var(--border-subtle)] hover:border-[var(--accent)] hover:bg-[var(--accent-soft)] text-sm text-[var(--text-secondary)] hover:text-white transition-all duration-200">
                        <x-lucide-message-circle class="w-4 h-4 text-[var(--accent)] flex-shrink-0" />
                        "Nega bunday bo'lganini tushuntirib bering"
                    </a>
                    <a href="{{ route('chat.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-black/20 border border-[var(--border-subtle)] hover:border-[var(--accent)] hover:bg-[var(--accent-soft)] text-sm text-[var(--text-secondary)] hover:text-white transition-all duration-200">
                        <x-lucide-sparkles class="w-4 h-4 text-[var(--gold)] flex-shrink-0" />
                        "Xuddi shunga o'xshash masala bering"
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
