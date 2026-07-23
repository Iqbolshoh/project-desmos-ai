@extends('layouts.dashboard')

@section('title', 'Mashq')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="quizForm()">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <x-dynamic-component :component="'lucide-' . $topic->icon" class="w-6 h-6 text-[var(--accent)]" />
            <h1 class="text-xl font-bold text-white">{{ $topic->name }}</h1>
            <span class="badge badge-accent capitalize">{{ $difficulty }}</span>
        </div>
        <div class="text-[var(--text-muted)] font-mono text-sm flex items-center gap-2 bg-[var(--bg-overlay)] px-3 py-1.5 rounded-lg border border-[var(--border-subtle)]">
            <x-lucide-timer class="w-4 h-4 text-[var(--text-secondary)]" /> 
            <span x-text="formatTime(timeSpent)">00:00</span>
        </div>
    </div>

    <!-- Question Card -->
    <div class="card p-6 border border-[var(--border-strong)]">
        <div class="text-white text-lg font-medium leading-relaxed mb-6">
            {!! nl2br(e($question->prompt)) !!}
        </div>
        
        @if($question->desmos_expressions)
            @php
                $exprs = json_decode($question->desmos_expressions, true) ?? [];
            @endphp
            <div class="mb-6 border border-[var(--border-subtle)] rounded-xl overflow-hidden shadow-lg bg-black">
                <x-desmos-calculator 
                    id="quiz-graph" 
                    height="250px" 
                    :expression="implode(',', $exprs)" 
                />
            </div>
        @endif

        <div class="space-y-4">
            @if($question->type === 'mcq' && $question->options)
                @php
                    $options = json_decode($question->options, true) ?? [];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($options as $letter => $text)
                    <label 
                        class="flex items-center gap-3 p-4 rounded-xl border transition-all cursor-pointer"
                        :class="{
                            'border-[var(--border-subtle)] bg-[var(--bg-overlay)] hover:bg-black/30 hover:border-[var(--accent-hover)]': !submitted,
                            'border-[var(--accent)] bg-[var(--accent-soft)]': selectedAnswer === '{{ $letter }}' && !submitted,
                            'border-green-500 bg-green-500/10': submitted && resultData.correct_answer === '{{ $letter }}',
                            'border-red-500 bg-red-500/10': submitted && selectedAnswer === '{{ $letter }}' && !resultData.is_correct
                        }"
                    >
                        <input 
                            type="radio" 
                            name="answer" 
                            value="{{ $letter }}" 
                            x-model="selectedAnswer"
                            :disabled="submitted"
                            class="w-5 h-5 text-[var(--accent)] bg-black/20 border-[var(--border-strong)] focus:ring-[var(--accent)] focus:ring-offset-black disabled:opacity-50" 
                        >
                        <span class="text-white font-medium" :class="{'opacity-70': submitted && resultData.correct_answer !== '{{ $letter }}'}">
                            <span class="font-bold text-[var(--text-muted)] mr-2">{{ $letter }})</span> {!! $text !!}
                        </span>
                        
                        <x-lucide-check-circle x-show="submitted && resultData.correct_answer === '{{ $letter }}'" class="w-5 h-5 text-green-500 ml-auto" />
                        <x-lucide-x-circle x-show="submitted && selectedAnswer === '{{ $letter }}' && !resultData.is_correct" class="w-5 h-5 text-red-500 ml-auto" />
                    </label>
                    @endforeach
                </div>
            @else
                <input 
                    type="text" 
                    x-model="selectedAnswer" 
                    :disabled="submitted"
                    class="input w-full md:w-1/2" 
                    placeholder="Javobni kiriting..."
                    :class="{
                        'border-green-500 focus:ring-green-500 text-green-400': submitted && resultData.is_correct,
                        'border-red-500 focus:ring-red-500 text-red-400': submitted && !resultData.is_correct
                    }"
                >
                <div x-show="submitted && !resultData.is_correct" class="text-green-400 font-bold mt-2 text-sm">
                    To'g'ri javob: <span x-text="resultData.correct_answer"></span>
                </div>
            @endif
        </div>
    </div>

    <!-- Result / Explanation -->
    <div x-show="submitted" x-transition.opacity class="card p-6 border shadow-xl" :class="resultData.is_correct ? 'border-[var(--gold-border)] bg-gradient-to-b from-[var(--gold-soft)] to-[var(--bg-raised)]' : 'border-[var(--border-strong)] bg-[var(--bg-overlay)]'" style="display: none;">
        <div class="flex gap-4">
            <div class="shrink-0">
                <div x-show="resultData.is_correct" class="w-12 h-12 rounded-full bg-[var(--gold)]/20 flex items-center justify-center">
                    <x-lucide-party-popper class="w-6 h-6 text-[var(--gold)]" />
                </div>
                <div x-show="!resultData.is_correct" class="w-12 h-12 rounded-full bg-red-500/20 flex items-center justify-center">
                    <x-lucide-info class="w-6 h-6 text-red-400" />
                </div>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white mb-2" x-text="resultData.is_correct ? 'Barakalla! To\'g\'ri javob.' : 'Noto\'g\'ri. Yechimni o\'rganing:'"></h3>
                <p class="text-[var(--text-secondary)] leading-relaxed" x-text="resultData.explanation"></p>
                
                <div class="mt-6 flex gap-4">
                    <a href="{{ route('practice.topic', $topic->slug) }}" class="btn-secondary">Mavzuga qaytish</a>
                    <button @click="location.reload()" class="btn-primary">Keyingi savol <x-lucide-arrow-right class="w-4 h-4" /></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions (Submit) -->
    <div x-show="!submitted" class="flex justify-end">
        <button 
            @click="submitAnswer" 
            :disabled="isSubmitting || !selectedAnswer"
            class="btn-primary shadow-lg shadow-[var(--accent-glow)] px-8 py-3 rounded-xl disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span x-show="!isSubmitting">Javobni tasdiqlash</span>
            <span x-show="isSubmitting" class="flex items-center gap-2">
                <x-lucide-loader-2 class="w-4 h-4 animate-spin" /> Tekshirilmoqda...
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
                    body: JSON.stringify({
                        answer: this.selectedAnswer,
                        time_spent: this.timeSpent
                    })
                });
                
                if (response.ok) {
                    this.resultData = await response.json();
                    this.submitted = true;
                } else {
                    alert("Xatolik yuz berdi. Qaytadan urinib ko'ring.");
                    this.timer = setInterval(() => { this.timeSpent++; }, 1000); // resume
                }
            } catch (e) {
                console.error(e);
                alert("Tarmoq xatosi.");
            } finally {
                this.isSubmitting = false;
            }
        }
    }));
});
</script>
@endsection
