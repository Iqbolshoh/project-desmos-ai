@extends('layouts.dashboard')

@section('title', 'AI Tutor')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Hero Header --}}
    <div class="relative overflow-hidden card border-[var(--accent-border)] bg-gradient-to-br from-[var(--bg-raised)] to-black rounded-3xl py-10 px-8">
        <div class="absolute inset-0 bg-gradient-to-br from-[var(--accent-soft)] to-transparent pointer-events-none"></div>
        <div class="absolute -right-10 -bottom-10 opacity-5">
            <x-lucide-bot class="w-64 h-64 text-[var(--accent)]" />
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center shadow-xl shadow-[var(--accent-glow)]">
                    <x-lucide-bot class="w-8 h-8 text-white" />
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-white">AI Tutor</h1>
                    <p class="text-[var(--text-secondary)] text-sm mt-0.5">Masalani kiriting — AI qadam-baqadam tushuntirib beradi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card p-8 border border-[var(--border-strong)] rounded-2xl">
        <form action="{{ route('tutor.solve') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="query" class="block text-sm font-semibold text-white mb-2">Masala sharti</label>
                <textarea
                    name="query"
                    id="query"
                    rows="5"
                    class="input bg-black/20 w-full resize-none focus:border-[var(--accent)] transition-colors"
                    placeholder="Masalan: Kvadrat tenglamani yeching x^2 - 4 = 0"
                    required
                ></textarea>
                @error('query')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-semibold text-white mb-2">
                    Yoki rasmini yuklang <span class="text-[var(--text-muted)] font-normal">(ixtiyoriy)</span>
                </label>
                <div class="border-2 border-dashed border-[var(--border-strong)] rounded-xl p-6 text-center hover:border-[var(--accent-border)] transition-colors cursor-pointer">
                    <x-lucide-image-plus class="w-8 h-8 text-[var(--text-muted)] mx-auto mb-3" />
                    <input
                        type="file"
                        name="image"
                        id="image"
                        accept="image/*"
                        class="block mx-auto text-sm text-[var(--text-secondary)]
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-xl file:border-0
                        file:text-sm file:font-semibold
                        file:bg-[var(--accent-soft)] file:text-[var(--accent-hover)]
                        hover:file:bg-[var(--accent)] hover:file:text-white transition-all cursor-pointer"
                    >
                    <p class="text-xs text-[var(--text-muted)] mt-2">PNG, JPG, GIF — max 5MB</p>
                </div>
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-alt)] hover:from-[var(--accent-hover)] hover:to-[var(--accent-alt)] text-white font-bold rounded-xl shadow-lg shadow-[var(--accent-glow)] transition-all duration-200 hover:-translate-y-0.5">
                    <x-lucide-zap class="w-5 h-5" /> Yechish
                </button>
            </div>
        </form>
    </div>

    {{-- Info hint --}}
    <div class="bg-[var(--bg-overlay)] rounded-2xl p-5 border border-[var(--border-subtle)] text-sm text-[var(--text-muted)] flex gap-3 items-start">
        <x-lucide-info class="w-5 h-5 text-[var(--accent)] flex-shrink-0 mt-0.5" />
        <div>
            <p><strong class="text-white">Qanday ishlaydi?</strong> Demo rejimida maxsus kalit so'zlar: <span class="text-[var(--accent-hover)] font-mono">kvadrat tenglama</span>, <span class="text-[var(--accent-hover)] font-mono">chiziq</span>, <span class="text-[var(--accent-hover)] font-mono">aylana</span>. Shu so'zlarni kiritsangiz demo yechim va grafik ko'rsatiladi.</p>
        </div>
    </div>
</div>
@endsection
