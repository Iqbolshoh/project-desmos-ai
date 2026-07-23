@extends('layouts.dashboard')

@section('title', 'Diagnostika')

@section('content')
<div class="max-w-3xl mx-auto mt-10 text-center space-y-6">
    <div class="w-24 h-24 mx-auto rounded-full bg-[var(--accent-soft)] flex items-center justify-center border-4 border-[var(--accent-border)] mb-6">
        <x-lucide-target class="w-12 h-12 text-[var(--accent-hover)]" />
    </div>
    
    <h1 class="text-3xl font-extrabold text-white">Bilimingizni aniqlaymiz</h1>
    <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-2xl mx-auto">
        Joriy SAT darajangizni bilish va o'zingizga moslashtirilgan o'quv rejasiga (Roadmap) ega bo'lish uchun qisqa diagnostik testdan o'ting.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 text-left">
        <div class="card p-5 bg-[var(--bg-overlay)] border-[var(--border-subtle)] text-center">
            <x-lucide-list-checks class="w-8 h-8 text-blue-400 mx-auto mb-2" />
            <div class="font-bold text-white text-lg">20 ta</div>
            <div class="text-sm text-[var(--text-muted)]">Savollar soni</div>
        </div>
        <div class="card p-5 bg-[var(--bg-overlay)] border-[var(--border-subtle)] text-center">
            <x-lucide-clock class="w-8 h-8 text-orange-400 mx-auto mb-2" />
            <div class="font-bold text-white text-lg">~15 daqiqa</div>
            <div class="text-sm text-[var(--text-muted)]">O'rtacha vaqt</div>
        </div>
        <div class="card p-5 bg-[var(--bg-overlay)] border-[var(--border-subtle)] text-center">
            <x-lucide-trending-up class="w-8 h-8 text-green-400 mx-auto mb-2" />
            <div class="font-bold text-white text-lg">Roadmap</div>
            <div class="text-sm text-[var(--text-muted)]">Natija asosida reja</div>
        </div>
    </div>

    <div class="pt-8">
        <a href="{{ route('diagnostic.show') }}" class="btn-primary text-lg px-8 py-4 shadow-xl shadow-[var(--accent-glow)] rounded-xl inline-flex items-center gap-3">
            Testni boshlash <x-lucide-arrow-right class="w-5 h-5" />
        </a>
    </div>
</div>
@endsection
