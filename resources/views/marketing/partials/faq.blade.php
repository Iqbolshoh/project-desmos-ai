@php
$faqs = [
    ['q' => "How does Desmos AI solve SAT Math problems?", 'a' => "You submit a problem via photo or text. The AI tutor analyzes the question, classifies the Digital SAT topic, plots the live expression on Desmos Graphing Calculator, and generates step-by-step reasoning."],
    ['q' => "Is Desmos AI free to use?", 'a' => "Yes! The Starter tier is free forever. You can also upgrade to Pro Scholar for unlimited AI tutoring, full practice bank access, and personalized roadmaps."],
    ['q' => "What if I have never used Desmos before?", 'a' => "No problem at all! Desmos AI guides you through every graphing trick, keyboard shortcut, and syntax needed to solve SAT math problems quickly."],
    ['q' => "How is the Study Roadmap generated?", 'a' => "Your roadmap is calculated based on your Diagnostic Placement Test results. It maps out weekly and monthly targets to take you from your current score to your 800 goal."],
];
@endphp

<section id="faq" class="max-w-3xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center">
        <span class="badge badge-gold font-mono">FAQ</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">Frequently Asked Questions</h2>
    </div>

    <div class="mt-12 space-y-3">
        @foreach ($faqs as $faq)
        <div class="card overflow-hidden rounded-2xl shadow-lg" x-data="{ open: false }">
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between gap-4 p-5 text-left cursor-pointer">
                <span class="font-semibold text-[var(--text-primary)] text-base">{{ $faq['q'] }}</span>
                <x-lucide-chevron-down class="w-4 h-4 shrink-0 text-[var(--gold)] transition-transform duration-300" x-bind:class="open && 'rotate-180'" />
            </button>
            <div x-show="open" x-collapse>
                <p class="px-5 pb-5 text-sm text-[var(--text-secondary)] leading-relaxed">{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
