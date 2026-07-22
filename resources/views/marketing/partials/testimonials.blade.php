@php
$testimonials = [
    ['name' => "Madina Yusupova", 'role' => "420 → 720 ball", 'text' => "Desmosda grafikni qanday chizishni tushunmasdim. AI har bir qadamni ko'rsatib berdi va endi masalalarni o'zim yecha olaman."],
    ['name' => "Sardor Aliyev", 'role' => "540 → 780 ball", 'text' => "Diagnostika testi zaif tomonlarimni aniq ko'rsatdi. Roadmap bo'yicha ishlash juda qulay bo'ldi."],
    ['name' => "Nilufar Rashidova", 'role' => "610 → 800 ball", 'text' => "Chat Tutor bilan istalgan vaqt savol berish imkoniyati eng foydali funksiya bo'ldi."],
];
@endphp

<section class="max-w-7xl mx-auto px-4 sm:px-8 py-20 sm:py-28">
    <div class="text-center max-w-2xl mx-auto">
        <span class="badge badge-gold">Fikrlar</span>
        <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">Talabalarimiz nima deydi</h2>
    </div>

    <div class="mt-14 grid grid-cols-1 lg:grid-cols-3 gap-5">
        @foreach ($testimonials as $t)
        <div class="card p-6">
            <x-lucide-quote class="w-6 h-6 text-[var(--gold)]" />
            <p class="mt-4 text-sm text-[var(--text-secondary)] leading-relaxed">{{ $t['text'] }}</p>
            <div class="mt-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[var(--gold-soft)] border border-[var(--gold-border)] flex items-center justify-center font-bold text-[var(--gold)]">
                    {{ mb_substr($t['name'], 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--text-primary)]">{{ $t['name'] }}</p>
                    <p class="text-xs text-[var(--text-muted)]">{{ $t['role'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
