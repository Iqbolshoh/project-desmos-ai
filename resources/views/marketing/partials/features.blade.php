@php
$features = [
    ['icon' => 'upload', 'title' => "Rasm yoki matn yuklang", 'desc' => "Masalani suratga oling yoki yozib yuboring — AI uni darhol tushunadi."],
    ['icon' => 'brain', 'title' => "AI mavzuni aniqlaydi", 'desc' => "Har bir masala uchun aniq SAT mavzusi va kerakli formula topiladi."],
    ['icon' => 'calculator', 'title' => "Desmos'da yechim", 'desc' => "AI masalani Desmos Graphing Calculator'da qanday yozishni ko'rsatadi."],
    ['icon' => 'route', 'title' => "Bosqichma-bosqich", 'desc' => "Har bir qadam alohida tushuntiriladi — nima uchun va qanday ishlashi."],
    ['icon' => 'clipboard-check', 'title' => "Diagnostika testi", 'desc' => "20 ta savol orqali joriy darajangiz aniqlanadi va zaif mavzular topiladi."],
    ['icon' => 'trending-up', 'title' => "Shaxsiy Roadmap", 'desc' => "Joriy balldan maqsad ballgacha bo'lgan haftalik/oylik reja tuziladi."],
];
@endphp

<section id="features" class="max-w-7xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto">
        <span class="badge badge-gold">Imkoniyatlar</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">Bitta platforma — to'liq tayyorgarlik</h2>
        <p class="mt-4 text-[var(--text-secondary)]">Har bir masaladan SAT strategiyasigacha, AI sizni qo'llab-quvvatlaydi.</p>
    </div>

    <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($features as $feature)
        <div class="card p-6 hover:border-[var(--gold-border)] transition-colors duration-300">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-[var(--gold-soft)] border border-[var(--gold-border)]">
                <x-dynamic-component :component="'lucide-' . $feature['icon']" class="w-5 h-5 text-[var(--gold)]" />
            </div>
            <h3 class="mt-4 font-bold text-[var(--text-primary)]">{{ $feature['title'] }}</h3>
            <p class="mt-2 text-sm text-[var(--text-secondary)] leading-relaxed">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>
