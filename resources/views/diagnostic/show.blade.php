@extends('layouts.dashboard')

@section('title', 'Diagnostik Test')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ answered: 0, total: {{ count($questions) }} }">

    {{-- Sticky header --}}
    <div class="sticky top-0 z-50 bg-[var(--bg-base)]/90 backdrop-blur-md py-3 -mx-4 px-4 border-b border-[var(--border-subtle)]">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <x-lucide-pen-tool class="w-6 h-6 text-[var(--gold)]" />
                Diagnostik Test
            </h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-[var(--text-muted)]">
                    Savollar: <span class="text-white font-bold">{{ count($questions) }}</span>
                </span>
                <div class="w-32 h-2 bg-[var(--bg-overlay)] rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] rounded-full transition-all duration-500"
                         :style="`width: ${Math.round(answered/total*100)}%`"></div>
                </div>
                <span class="text-[var(--gold)] font-bold text-sm font-mono" x-text="`${Math.round(answered/total*100)}%`"></span>
            </div>
        </div>
    </div>

    <form action="{{ route('diagnostic.submit') }}" method="POST">
        @csrf
        <div class="space-y-6">
            @foreach($questions as $index => $q)
                <div class="card p-6 border border-[var(--border-strong)] hover:border-[var(--gold-border)] transition-all duration-300 rounded-2xl group"
                     x-data="{ selected: null }"
                     @change="answered = document.querySelectorAll('[name^=answers]:checked, [name^=answers][type=text]:not([value=])').length">

                    {{-- Question number + prompt --}}
                    <div class="flex gap-4 mb-5">
                        <div class="w-10 h-10 shrink-0 rounded-xl bg-gradient-to-br from-[var(--gold-soft)] to-[var(--bg-raised)] text-[var(--gold)] flex items-center justify-center font-bold text-sm border border-[var(--gold-border)] shadow-inner">
                            {{ $index + 1 }}
                        </div>
                        <div class="text-white text-base font-medium leading-relaxed mt-1.5">
                            {!! nl2br(e($q->prompt)) !!}
                        </div>
                    </div>

                    @if($q->desmos_expressions)
                        @php $exprs = json_decode($q->desmos_expressions, true) ?? []; @endphp
                        <div class="mb-5 ml-14 border border-[var(--border-subtle)] rounded-xl overflow-hidden shadow-lg bg-black">
                            <x-desmos-calculator
                                :id="'graph-'.$q->id"
                                height="220px"
                                :expression="implode(',', $exprs)"
                            />
                        </div>
                    @endif

                    <div class="ml-14 space-y-3">
                        @if($q->type === 'mcq' && $q->options)
                            @php $options = json_decode($q->options, true) ?? []; @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($options as $letter => $text)
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-[var(--border-subtle)] bg-[var(--bg-overlay)]/50 hover:bg-[var(--gold-soft)] hover:border-[var(--gold-border)] cursor-pointer transition-all duration-200 group/opt has-[:checked]:bg-[var(--gold-soft)] has-[:checked]:border-[var(--gold)] has-[:checked]:shadow-lg has-[:checked]:shadow-[var(--gold-glow)]">
                                    <div class="w-6 h-6 rounded-full border-2 border-[var(--border-strong)] group-hover/opt:border-[var(--gold)] has-[:checked]:border-[var(--gold)] flex items-center justify-center shrink-0 transition-colors">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $letter }}" class="sr-only" required>
                                        <div class="w-2.5 h-2.5 rounded-full bg-[var(--gold)] opacity-0 peer-checked:opacity-100 scale-0 transition-all" :class="selected === '{{ $letter }}' ? 'opacity-100 scale-100' : ''"></div>
                                    </div>
                                    <span class="text-white text-sm font-medium"><span class="font-bold text-[var(--gold)] mr-2">{{ $letter }})</span>{!! $text !!}</span>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <input type="text" name="answers[{{ $q->id }}]"
                                   class="input w-full md:w-1/2 bg-black/20 border-[var(--border-strong)] focus:border-[var(--gold)] focus:ring-[var(--gold-glow)] transition-colors"
                                   placeholder="Javobingizni kiriting..."
                                   required>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 sticky bottom-6 z-50 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] hover:from-[var(--gold)] hover:to-[var(--gold-alt)] text-white font-bold text-lg rounded-2xl shadow-2xl shadow-[var(--gold-glow)] transition-all duration-300 hover:-translate-y-1">
                <x-lucide-check-circle class="w-6 h-6" />
                Testni yakunlash
            </button>
        </div>
    </form>
</div>
@endsection

