@extends('layouts.dashboard')

@section('title', 'AI Tutor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-bot class="w-7 h-7 text-[var(--accent)]" />
            AI Tutor
        </h1>
        <p class="text-[var(--text-secondary)] mt-1">Masalani kiriting yoki rasmini yuklang, AI yechimni tushuntirib beradi.</p>
    </div>

    <div class="card p-6 border border-[var(--border-strong)] bg-gradient-to-b from-[var(--bg-raised)] to-[var(--bg-surface)]">
        <form action="{{ route('tutor.solve') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label for="query" class="block text-sm font-semibold text-white mb-2">Masala sharti</label>
                <textarea 
                    name="query" 
                    id="query" 
                    rows="4" 
                    class="input bg-black/20 w-full" 
                    placeholder="Masalan: Kvadrat tenglamani yeching x^2 - 4 = 0"
                    required
                ></textarea>
                @error('query')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-semibold text-white mb-2">Yoki rasmini yuklang (ixtiyoriy)</label>
                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    accept="image/*"
                    class="block w-full text-sm text-[var(--text-secondary)]
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-[var(--accent-soft)] file:text-[var(--accent-hover)]
                    hover:file:bg-[var(--accent-hover)] hover:file:text-white transition-all cursor-pointer"
                >
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="btn-primary shadow-lg shadow-[var(--accent-glow)]">
                    <x-lucide-zap class="w-4 h-4" /> Yechish
                </button>
            </div>
        </form>
    </div>
    
    <div class="bg-[var(--bg-overlay)] rounded-xl p-5 border border-[var(--border-subtle)] text-sm text-[var(--text-muted)] flex gap-3 items-start">
        <x-lucide-info class="w-5 h-5 text-[var(--accent)] flex-shrink-0 mt-0.5" />
        <div>
            <p><strong class="text-white">Qanday ishlaydi?</strong> Hozirda demo rejimidasiz. Maxsus kalit so'zlar: <span class="text-[var(--accent-hover)] font-mono">kvadrat tenglama</span>, <span class="text-[var(--accent-hover)] font-mono">chiziq</span>, <span class="text-[var(--accent-hover)] font-mono">aylana</span>. Shu so'zlarni kiritsangiz demo yechim va grafik ko'rsatiladi.</p>
        </div>
    </div>
</div>
@endsection
