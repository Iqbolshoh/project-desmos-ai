<section class="relative overflow-hidden">
    <div class="absolute inset-0 z-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-[-15%] left-[-10%] w-[36rem] h-[36rem] rounded-full blur-[140px]" style="background: var(--gold-glow);"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[32rem] h-[32rem] rounded-full blur-[140px]" style="background: rgba(59, 130, 246, 0.10);"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-8 pt-20 sm:pt-28 pb-16 text-center page-enter">
        <span class="badge badge-gold mx-auto font-mono">
            <x-lucide-sparkles class="w-3.5 h-3.5" />
            AI + Live Desmos Graphing Engine
        </span>

        <h1 class="mt-6 text-4xl sm:text-6xl font-extrabold tracking-tight leading-[1.05]">
            Master Digital SAT Math
            <span class="gold-text">800/800</span>
            With AI Power
        </h1>

        <p class="mt-6 text-base sm:text-lg text-[var(--text-secondary)] max-w-2xl mx-auto leading-relaxed">
            Upload any SAT math problem photo or text — AI instantly identifies key concepts, generates exact step-by-step solutions, and plots live interactive
            <span class="text-[var(--text-primary)] font-semibold">Desmos Graphing Calculator</span> expressions.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="btn-gold px-8 py-3.5 text-base font-extrabold">
                <x-lucide-zap class="w-5 h-5" />
                Start Free Practice
            </a>
            <a href="#features" class="btn-secondary px-8 py-3.5 text-base">
                How It Works
                <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>

        <div class="mt-16 card p-3 sm:p-4 max-w-4xl mx-auto border border-[var(--gold-border)] shadow-2xl shadow-black/50">
            <div class="rounded-[calc(var(--radius-lg)-0.25rem)] overflow-hidden bg-[var(--bg-base)] border border-[var(--border-subtle)] aspect-video flex items-center justify-center">
                <div class="text-center px-6">
                    <x-lucide-calculator class="w-12 h-12 mx-auto text-[var(--gold)]" />
                    <p class="mt-4 text-sm text-[var(--text-muted)]">Interactive Desmos Graphing Widget & Step-by-Step AI Solutions</p>
                </div>
            </div>
        </div>
    </div>
</section>
