@php
$faqs = [
    ['q' => "Desmos AI qanday ishlaydi?", 'a' => "Siz masalani rasm yoki matn ko'rinishida yuborasiz, AI uni tahlil qiladi, SAT mavzusini aniqlaydi va Desmos Graphing Calculator yordamida bosqichma-bosqich yechim ko'rsatadi."],
    ['q' => "Bu bepulmi?", 'a' => "Demo versiyada asosiy funksiyalardan bepul foydalanish mumkin. To'liq imkoniyatlar uchun Premium reja mavjud."],
    ['q' => "Desmos'ni oldin ishlatmagan bo'lsam ham foydalana olamanmi?", 'a' => "Ha. AI har bir amaliyotni tushuntirib beradi, shu jumladan Desmos'da ifodani qanday yozishni ham ko'rsatadi."],
    ['q' => "Roadmap qanday tuziladi?", 'a' => "Diagnostika testi natijalari asosida joriy balingiz aniqlanadi, so'ng maqsad ballgacha bo'lgan haftalik va oylik reja avtomatik tuziladi."],
];
@endphp

<section id="faq" class="max-w-3xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center">
        <span class="badge badge-gold">Savollar</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">Ko'p so'raladigan savollar</h2>
    </div>

    <div class="mt-12 space-y-3" x-data="{ open: 0 }">
        @foreach ($faqs as $index => $faq)
        <div class="card overflow-hidden">
            <button type="button" @click="open = (open === {{ $index }} ? null : {{ $index }})"
                class="w-full flex items-center justify-between gap-4 p-5 text-left cursor-pointer">
                <span class="font-semibold text-[var(--text-primary)]">{{ $faq['q'] }}</span>
                <x-lucide-chevron-down class="w-4 h-4 shrink-0 text-[var(--gold)] transition-transform duration-300"
                    :class="open === {{ $index }} ? 'rotate-180' : ''" />
            </button>
            <div x-show="open === {{ $index }}" x-collapse>
                <p class="px-5 pb-5 text-sm text-[var(--text-secondary)] leading-relaxed">{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
