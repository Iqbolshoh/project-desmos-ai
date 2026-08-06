@php
$features = [
    ['icon' => 'upload', 'title' => "Upload Image or Text", 'desc' => "Snap a photo of any SAT math problem or paste text — AI understands it instantly."],
    ['icon' => 'brain', 'title' => "Smart Topic Classification", 'desc' => "Identifies precise Digital SAT math domains and required core formulas."],
    ['icon' => 'calculator', 'title' => "Live Desmos Graphing Engine", 'desc' => "Generates interactive Desmos calculator expressions and visual graphs."],
    ['icon' => 'route', 'title' => "Step-by-Step AI Solutions", 'desc' => "Clear explanations for every calculation step to build conceptual intuition."],
    ['icon' => 'clipboard-check', 'title' => "Diagnostic Placement Test", 'desc' => "20-question benchmark assessment to pinpoint your weak and strong math topics."],
    ['icon' => 'trending-up', 'title' => "Personalized Study Roadmap", 'desc' => "Tailored weekly and monthly study milestones from your current score to your 800 goal."],
];
@endphp

<section id="features" class="max-w-7xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto">
        <span class="badge badge-gold font-mono">Platform Capabilities</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">One Unified AI Engine for Digital SAT Math</h2>
        <p class="mt-4 text-[var(--text-secondary)]">From problem solving to long-term study planning, Desmos AI handles every aspect of your preparation.</p>
    </div>

    <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($features as $feature)
        <div class="card p-6 hover:border-[var(--gold-border)] transition-colors duration-300 rounded-2xl shadow-xl">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[var(--gold-soft)] border border-[var(--gold-border)] shadow-md">
                <x-dynamic-component :component="'lucide-' . $feature['icon']" class="w-5 h-5 text-[var(--gold)]" />
            </div>
            <h3 class="mt-4 font-bold text-[var(--text-primary)] text-base">{{ $feature['title'] }}</h3>
            <p class="mt-2 text-sm text-[var(--text-secondary)] leading-relaxed">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>
