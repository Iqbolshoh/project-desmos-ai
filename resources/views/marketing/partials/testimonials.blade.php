@php
$testimonials = [
    ['name' => "Madina Yusupova", 'role' => "420 → 720 SAT Score", 'text' => "I used to struggle with Desmos shortcuts. Desmos AI showed step-by-step visual graphs, allowing me to master system equations effortlessly."],
    ['name' => "Sardor Aliyev", 'role' => "540 → 780 SAT Score", 'text' => "The diagnostic placement test pinpointed my weaknesses immediately. Following the AI study roadmap helped me gain 240+ points."],
    ['name' => "Nilufar Rashidova", 'role' => "610 → 800 Perfect Score", 'text' => "Having 24/7 access to the AI Chat Tutor to ask instant geometry and algebra questions made all the difference in reaching an 800."],
];
@endphp

<section class="max-w-7xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto">
        <span class="badge badge-gold font-mono">Student Success</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">What Our Students Say</h2>
    </div>

    <div class="mt-14 grid grid-cols-1 lg:grid-cols-3 gap-5">
        @foreach ($testimonials as $t)
        <div class="card p-6 rounded-2xl shadow-xl hover:border-[var(--gold-border)] transition-colors">
            <x-lucide-quote class="w-6 h-6 text-[var(--gold)]" />
            <p class="mt-4 text-sm text-[var(--text-secondary)] leading-relaxed">{{ $t['text'] }}</p>
            <div class="mt-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[var(--gold-soft)] border border-[var(--gold-border)] flex items-center justify-center font-bold text-[var(--gold)]">
                    {{ mb_substr($t['name'], 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--text-primary)]">{{ $t['name'] }}</p>
                    <p class="text-xs font-mono text-[var(--gold-hover)] font-bold">{{ $t['role'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
