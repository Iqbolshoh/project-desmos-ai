@extends('layouts.dashboard')

@section('title', 'Diagnostik Test')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-pen-tool class="w-7 h-7 text-[var(--accent)]" />
            Diagnostik Test
        </h1>
        <div class="text-[var(--text-muted)] text-sm font-semibold px-4 py-2 bg-[var(--bg-overlay)] rounded-lg border border-[var(--border-subtle)]">
            Jami savollar: {{ count($questions) }}
        </div>
    </div>

    <form action="{{ route('diagnostic.submit') }}" method="POST">
        @csrf
        
        <div class="space-y-8">
            @foreach($questions as $index => $q)
                <div class="card p-6 border border-[var(--border-strong)]">
                    <div class="flex gap-4 mb-4">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[var(--accent-soft)] text-[var(--accent-hover)] flex items-center justify-center font-bold text-sm border border-[var(--accent-border)]">
                            {{ $index + 1 }}
                        </div>
                        <div class="text-white text-lg font-medium leading-relaxed mt-0.5">
                            {!! nl2br(e($q->prompt)) !!}
                        </div>
                    </div>
                    
                    @if($q->desmos_expressions)
                        @php
                            $exprs = json_decode($q->desmos_expressions, true) ?? [];
                        @endphp
                        <div class="mb-5 ml-12 border border-[var(--border-subtle)] rounded-xl overflow-hidden shadow-lg bg-black">
                            <x-desmos-calculator 
                                :id="'graph-'.$q->id" 
                                height="250px" 
                                :expression="implode(',', $exprs)" 
                            />
                        </div>
                    @endif

                    <div class="ml-12 mt-4 space-y-3">
                        @if($q->type === 'mcq' && $q->options)
                            @php
                                $options = json_decode($q->options, true) ?? [];
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($options as $letter => $text)
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-[var(--border-subtle)] bg-[var(--bg-overlay)] hover:bg-black/30 hover:border-[var(--accent-hover)] cursor-pointer transition-all has-[:checked]:bg-[var(--accent-soft)] has-[:checked]:border-[var(--accent)]">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $letter }}" class="w-5 h-5 text-[var(--accent)] bg-black/20 border-[var(--border-strong)] focus:ring-[var(--accent)] focus:ring-offset-black" required>
                                    <span class="text-white font-medium"><span class="font-bold text-[var(--text-muted)] mr-2">{{ $letter }})</span> {!! $text !!}</span>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <input type="text" name="answers[{{ $q->id }}]" class="input w-full md:w-1/2" placeholder="Javobni kiriting..." required>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-end sticky bottom-6 z-50">
            <button type="submit" class="btn-primary px-8 py-4 shadow-2xl shadow-[var(--accent-glow)] rounded-xl text-lg w-full md:w-auto">
                <x-lucide-check-circle class="w-5 h-5" />
                Testni yakunlash
            </button>
        </div>
    </form>
</div>
@endsection
