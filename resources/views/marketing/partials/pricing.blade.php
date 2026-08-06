@php
$plans = [
    [
        'name' => "Starter",
        'price' => "$0",
        'period' => "forever free",
        'featured' => false,
        'features' => ["Diagnostic placement test", "3 AI tutor solutions daily", "Limited practice bank access", "Basic student dashboard"],
    ],
    [
        'name' => "Pro Scholar",
        'price' => "$19",
        'period' => "per month",
        'featured' => true,
        'features' => ["Unlimited AI tutor solutions", "Full SAT practice question bank", "Personalized study roadmap", "24/7 AI Chat Tutor", "Saved graphs & session history", "Gamification & achievement badges"],
    ],
    [
        'name' => "Institutional",
        'price' => "Custom",
        'period' => "per school / group",
        'featured' => false,
        'features' => ["All Pro Scholar features", "Teacher & Admin dashboard", "Classroom analytics & reports", "Multi-student management & roles"],
    ],
];
@endphp

<section id="pricing" class="max-w-7xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto">
        <span class="badge badge-gold font-mono">Simple Pricing</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">Choose the Plan for Your Goal</h2>
        <p class="mt-4 text-[var(--text-secondary)]">Start free and upgrade anytime as you progress toward your target score.</p>
    </div>

    <div class="mt-14 grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        @foreach ($plans as $plan)
        <div class="card p-8 rounded-2xl {{ $plan['featured'] ? 'border-2 border-[var(--gold)] relative lg:-translate-y-3 shadow-2xl shadow-[var(--gold-glow)]' : 'shadow-xl' }}">
            @if ($plan['featured'])
            <span class="badge badge-gold absolute -top-3 left-1/2 -translate-x-1/2 font-mono">Most Popular</span>
            @endif

            <h3 class="text-lg font-bold text-[var(--text-primary)]">{{ $plan['name'] }}</h3>
            <p class="mt-4">
                <span class="text-3xl font-extrabold font-mono {{ $plan['featured'] ? 'gold-text' : '' }}">{{ $plan['price'] }}</span>
                <span class="text-xs text-[var(--text-muted)] font-mono"> / {{ $plan['period'] }}</span>
            </p>

            <ul class="mt-6 space-y-3">
                @foreach ($plan['features'] as $feature)
                <li class="flex items-start gap-2.5 text-sm text-[var(--text-secondary)]">
                    <x-lucide-check-circle class="w-4 h-4 mt-0.5 shrink-0 text-[var(--gold)]" />
                    {{ $feature }}
                </li>
                @endforeach
            </ul>

            <a href="{{ route('register') }}" class="mt-8 w-full {{ $plan['featured'] ? 'btn-gold' : 'btn-secondary' }}">
                Get Started
            </a>
        </div>
        @endforeach
    </div>
</section>
