@extends('layouts.dashboard')

@section('title', 'Diagnostika')

@section('content')
<div class="max-w-3xl mx-auto mt-10 text-center space-y-6 relative group">
    <!-- Decorative background glow -->
    <div class="absolute -inset-x-20 -inset-y-10 bg-gradient-to-r from-[var(--gold-soft)] to-[var(--accent-soft)] blur-3xl opacity-30 rounded-full pointer-events-none group-hover:opacity-50 transition duration-700"></div>

    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-[var(--bg-surface)] to-[var(--bg-raised)] flex items-center justify-center border-2 border-[var(--gold-border)] mb-6 shadow-xl shadow-[var(--gold-glow)] relative z-10 transition-transform duration-500 hover:scale-110">
        <x-lucide-target class="w-12 h-12 text-[var(--gold)]" />
    </div>
    
    <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-[var(--gold-alt)] to-[var(--gold)] relative z-10">
        Bilimingizni aniqlaymiz
    </h1>
    <p class="text-[var(--text-secondary)] text-lg md:text-xl leading-relaxed max-w-2xl mx-auto relative z-10">
        Joriy SAT darajangizni bilish va o'zingizga moslashtirilgan o'quv rejasiga (Roadmap) ega bo'lish uchun qisqa diagnostik testdan o'ting.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 text-left relative z-10">
        <div class="card p-6 bg-white/5 backdrop-blur-md border border-[var(--border-subtle)] hover:border-[var(--gold-border)] transition-all duration-300 rounded-2xl text-center group/card">
            <div class="w-14 h-14 mx-auto bg-[var(--accent-soft)] rounded-xl flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300">
                <x-lucide-list-checks class="w-7 h-7 text-[var(--accent)]" />
            </div>
            <div class="font-bold text-white text-2xl">20 ta</div>
            <div class="text-sm text-[var(--text-muted)] font-medium uppercase tracking-wider mt-1">Savollar soni</div>
        </div>
        
        <div class="card p-6 bg-white/5 backdrop-blur-md border border-[var(--border-subtle)] hover:border-[var(--gold-border)] transition-all duration-300 rounded-2xl text-center group/card">
            <div class="w-14 h-14 mx-auto bg-orange-500/10 rounded-xl flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300">
                <x-lucide-clock class="w-7 h-7 text-orange-400" />
            </div>
            <div class="font-bold text-white text-2xl">~15 daqiqa</div>
            <div class="text-sm text-[var(--text-muted)] font-medium uppercase tracking-wider mt-1">O'rtacha vaqt</div>
        </div>
        
        <div class="card p-6 bg-white/5 backdrop-blur-md border border-[var(--border-subtle)] hover:border-[var(--gold-border)] transition-all duration-300 rounded-2xl text-center group/card relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[var(--gold-soft)] to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-300"></div>
            <div class="w-14 h-14 mx-auto bg-[var(--gold-soft)] rounded-xl flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 relative z-10">
                <x-lucide-trending-up class="w-7 h-7 text-[var(--gold)]" />
            </div>
            <div class="font-bold text-[var(--gold-alt)] text-2xl relative z-10">Roadmap</div>
            <div class="text-sm text-[var(--text-muted)] font-medium uppercase tracking-wider mt-1 relative z-10">Shaxsiy reja</div>
        </div>
    </div>

    <div class="pt-12 relative z-10">
        <a href="{{ route('diagnostic.show') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-[var(--gold-deep)] to-[var(--gold)] hover:from-[var(--gold)] hover:to-[var(--gold-alt)] text-white font-bold text-xl rounded-2xl shadow-2xl shadow-[var(--gold-glow)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[var(--gold-soft)]">
            Testni boshlash <x-lucide-arrow-right class="w-6 h-6 animate-pulse" />
        </a>
    </div>
</div>
@endsection
