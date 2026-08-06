@extends('layouts.dashboard')

@section('title', 'Practice Quiz')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="quizForm()">

    {{-- Quiz Header --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('practice.topic', $topic->slug) }}" class="w-9 h-9 rounded-xl border border-[var(--border-strong)] bg-[var(--bg-overlay)] flex items-center justify-center hover:border-[var(--accent-border)] hover:bg-[var(--accent-soft)] transition-all">
                <x-lucide-arrow-left class="w-4 h-4 text-[var(--text-secondary)]" />
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-5 h-5 text-[var(--accent)]" />
                    <h1 class="text-lg font-bold text-white">{{ $topic->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold capitalize font-mono
                        {{ $difficulty === 'easy' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25' :
                           ($difficulty === 'hard' ? 'bg-red-500/15 text-red-400 border border-red-500/25' :
                           'bg-amber-500/15 text-amber-400 border border-amber-500/25') }}">
                        {{ $difficulty === 'easy' ? 'Easy' : ($difficulty === 'hard' ? 'Hard' : 'Medium') }}
                    </span>
                </div>
            </div>
        </div>
        {{-- Timer --}}
        <div class="flex items-center gap-2 px-4 py-2 bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-xl font-mono text-sm text-white">
            <x-lucide-timer class="w-4 h-4 text-[var(--accent)]" />
            <span x-text="formatTime(timeSpent)">00:00</span>
        </div>
    </div>

    {{-- Question Card --}}
    <div class="card p-7 border border-[var(--border-strong)] rounded-2xl shadow-xl">
        <p class="text-white text-base font-medium leading-relaxed mb-6">
            {!! nl2br(e($question->prompt)) !!}
        </p>

        @if($question->desmos_expressions)
            @php $exprs = is_array($question->desmos_expressions) ? $question->desmos_expressions : (json_decode($question->desmos_expressions, true) ?? []); @endphp
            <div class="mb-6 border border-[var(--border-subtle)] rounded-xl overflow-hidden shadow-lg bg-black">
                <x-desmos-calculator id="quiz-graph" height="250px" :expression="implode(',', $exprs)" />
            </div>
        @endif

        <div class="space-y-4">
            @if($question->type === 'mcq' && $question->options)
                @php $options = is_array($question->options) ? $question->options : (json_decode($question->options, true) ?? []); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($options as $letter => $text)
                    <label
                        class="flex items-center gap-3 p-4 rounded-xl border transition-all cursor-pointer select-none"
                        :class="{
                            'border-[var(--border-subtle)] bg-[var(--bg-overlay)] hover:bg-[var(--bg-raised)] hover:border-[var(--accent-border)]': !submitted && selectedAnswer !== '{{ $letter }}',
                            'border-[var(--accent)] bg-[var(--accent-soft)] shadow-md shadow-[var(--accent-glow)]': selectedAnswer === '{{ $letter }}' && !submitted,
                            'border-emerald-500 bg-emerald-500/10': submitted && resultData.correct_answer === '{{ $letter }}',
                            'border-red-500 bg-red-500/10': submitted && selectedAnswer === '{{ $letter }}' && !resultData.is_correct
                        }"
                    >
                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 transition-all"
                             :class="{
                                'border-[var(--border-strong)]': selectedAnswer !== '{{ $letter }}' && !submitted,
                                'border-[var(--accent)] bg-[var(--accent)]': selectedAnswer === '{{ $letter }}' && !submitted,
                                'border-emerald-500 bg-emerald-500': submitted && resultData.correct_answer === '{{ $letter }}',
                                'border-red-500 bg-red-500': submitted && selectedAnswer === '{{ $letter }}' && !resultData.is_correct
                             }">
                            <input type="radio" name="answer" value="{{ $letter }}" x-model="selectedAnswer" :disabled="submitted" class="sr-only">
                            <x-lucide-check class="w-3 h-3 text-white" x-show="selectedAnswer === '{{ $letter }}' || (submitted && resultData.correct_answer === '{{ $letter }}')" />
                        </div>
                        <span class="text-white text-sm font-medium flex-1">
                            <span class="font-bold text-[var(--gold)] mr-1.5">{{ $letter }})</span>{!! $text !!}
                        </span>
                        <x-lucide-check-circle x-show="submitted && resultData.correct_answer === '{{ $letter }}'" class="w-5 h-5 text-emerald-500 ml-auto shrink-0" style="display:none" />
                        <x-lucide-x-circle x-show="submitted && selectedAnswer === '{{ $letter }}' && !resultData.is_correct" class="w-5 h-5 text-red-500 ml-auto shrink-0" style="display:none" />
                    </label>
                    @endforeach
                </div>
            @else
                <div>
                    <input
                        type="text"
                        x-model="selectedAnswer"
                        :disabled="submitted"
                        class="input w-full md:w-1/2 focus:border-[var(--accent)] transition-colors bg-black/30"
                        placeholder="Type your answer..."
                        :class="{
                            'border-emerald-500 text-emerald-400': submitted && resultData.is_correct,
                            'border-red-500 text-red-400': submitted && !resultData.is_correct
                        }"
                    >
                    <div x-show="submitted && !resultData.is_correct" class="text-emerald-400 font-bold mt-2 text-sm" style="display:none">
                        ✓ Correct Answer: <span x-text="resultData.correct_answer"></span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Result / Explanation --}}
    <div x-show="submitted" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         class="card p-6 border shadow-xl rounded-2xl"
         :class="resultData.is_correct ? 'border-[var(--gold-border)] bg-gradient-to-b from-[var(--gold-soft)] to-[var(--bg-raised)]' : 'border-red-500/20 bg-red-500/5'"
         style="display: none;">
        <div class="flex gap-4">
            <div class="shrink-0">
                <div x-show="resultData.is_correct" class="w-12 h-12 rounded-xl bg-[var(--gold)]/20 border border-[var(--gold-border)] flex items-center justify-center">
                    <x-lucide-party-popper class="w-6 h-6 text-[var(--gold)]" />
                </div>
                <div x-show="!resultData.is_correct" class="w-12 h-12 rounded-xl bg-red-500/20 border border-red-500/30 flex items-center justify-center">
                    <x-lucide-x class="w-6 h-6 text-red-400" />
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-white mb-2" x-text="resultData.is_correct ? '🎉 Excellent! Correct Answer.' : '❌ Incorrect. Review explanation below:'"></h3>
                <p class="text-[var(--text-secondary)] leading-relaxed" x-text="resultData.explanation"></p>

                <div class="mt-6 flex gap-3 flex-wrap">
                    <a href="{{ route('practice.topic', $topic->slug) }}" class="btn-secondary text-sm">
                        <x-lucide-arrow-left class="w-4 h-4" /> Return to Topic
                    </a>
                    <button @click="location.reload()" class="btn-primary text-sm">
                        Next Question <x-lucide-arrow-right class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Button --}}
    <div x-show="!submitted" class="flex justify-end">
        <button
            @click="submitAnswer"
            :disabled="isSubmitting || !selectedAnswer"
            class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-alt)] hover:from-[var(--accent-hover)] text-white font-bold rounded-xl shadow-lg shadow-[var(--accent-glow)] disabled:opacity-40 disabled:cursor-not-allowed transition-all hover:-translate-y-0.5">
            <span x-show="!isSubmitting">
                <span class="flex items-center gap-2"><x-lucide-check class="w-5 h-5" /> Confirm Answer</span>
            </span>
            <span x-show="isSubmitting" class="flex items-center gap-2">
                <x-lucide-loader-2 class="w-4 h-4 animate-spin" /> Checking...
            </span>
        </button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('quizForm', () => ({
        selectedAnswer: '',
        submitted: false,
        isSubmitting: false,
        timeSpent: 0,
        timer: null,
        resultData: {},

        init() {
            this.timer = setInterval(() => {
                if (!this.submitted) this.timeSpent++;
            }, 1000);
        },

        formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },

        async submitAnswer() {
            if (!this.selectedAnswer) return;
            this.isSubmitting = true;
            clearInterval(this.timer);

            try {
                const response = await fetch("{{ route('practice.submit', $question->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ answer: this.selectedAnswer, time_spent: this.timeSpent })
                });

                if (response.ok) {
                    this.resultData = await response.json();
                    this.submitted = true;
                } else {
                    alert("An error occurred. Please try again.");
                    this.timer = setInterval(() => { this.timeSpent++; }, 1000);
                }
            } catch (e) {
                console.error(e);
                alert("Network error.");
            } finally {
                this.isSubmitting = false;
            }
        }
    }));
});
</script>
@endsection
