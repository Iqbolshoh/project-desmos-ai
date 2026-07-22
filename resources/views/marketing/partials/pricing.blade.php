@php
$plans = [
    [
        'name' => "Bepul",
        'price' => "0",
        'period' => "har doim",
        'featured' => false,
        'features' => ["Diagnostika testi", "Kuniga 3 ta AI yechim", "Practice bo'limi (cheklangan)", "Asosiy dashboard"],
    ],
    [
        'name' => "Premium",
        'price' => "149,000",
        'period' => "oyiga, so'm",
        'featured' => true,
        'features' => ["Cheksiz AI Tutor", "To'liq Practice bank", "Shaxsiy Roadmap", "AI Chat Tutor", "Tarix va saqlangan grafiklar", "Gamifikatsiya va yutuqlar"],
    ],
    [
        'name' => "Maktab",
        'price' => "Kelishilgan",
        'period' => "guruhlar uchun",
        'featured' => false,
        'features' => ["Premium'ning barcha imkoniyatlari", "O'qituvchi/Admin panel", "Hisobotlar va analitika", "Ko'p talabali boshqaruv"],
    ],
];
@endphp

<section id="pricing" class="max-w-7xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto">
        <span class="badge badge-gold">Narxlar</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">Sizga mos rejani tanlang</h2>
        <p class="mt-4 text-[var(--text-secondary)]">Demo versiyada barcha rejalar namoyish uchun ko'rsatilgan.</p>
    </div>

    <div class="mt-14 grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        @foreach ($plans as $plan)
        <div class="card p-8 {{ $plan['featured'] ? 'border-2 border-[var(--gold)] relative lg:-translate-y-3 shadow-2xl shadow-black/40' : '' }}">
            @if ($plan['featured'])
            <span class="badge badge-gold absolute -top-3 left-1/2 -translate-x-1/2">Eng mashhur</span>
            @endif

            <h3 class="text-lg font-bold text-[var(--text-primary)]">{{ $plan['name'] }}</h3>
            <p class="mt-4">
                <span class="text-3xl font-extrabold {{ $plan['featured'] ? 'gold-text' : '' }}">{{ $plan['price'] }}</span>
                <span class="text-sm text-[var(--text-muted)]"> / {{ $plan['period'] }}</span>
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
                Boshlash
            </a>
        </div>
        @endforeach
    </div>
</section>
