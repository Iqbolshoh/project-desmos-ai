@extends('layouts.dashboard')

@section('title', 'AI Tutor — SAT Math')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Hero Header --}}
    <div class="relative overflow-hidden card border-[var(--gold-border)] bg-gradient-to-br from-[var(--bg-raised)] via-[#0F1E2B] to-black rounded-3xl py-10 px-8 shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-br from-[var(--amber-glow)] via-transparent to-transparent pointer-events-none"></div>
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#F2A93B] to-[#EF6461] flex items-center justify-center shadow-xl shadow-[var(--gold-glow)]">
                    <x-lucide-bot class="w-8 h-8 text-black" />
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">AI Desmos Tutor</h1>
                    <p class="text-[var(--text-secondary)] text-sm mt-0.5">Type your SAT Math problem or upload a photo of it — AI will solve it in Desmos syntax</p>
                </div>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/40 border border-[var(--gold-border)] text-xs font-mono text-[var(--gold)]">
                <span class="w-2 h-2 rounded-full bg-[var(--teal)] animate-pulse"></span> Claude 3.5 Sonnet + Desmos 1.9
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card p-8 border border-[var(--border-strong)] rounded-2xl bg-[var(--panel)] shadow-xl">
        <form action="{{ route('tutor.solve') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="solveForm">
            @csrf

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="query" class="block text-sm font-semibold text-white">Problem statement</label>
                    <span class="text-xs text-[var(--text-muted)] font-mono">Formula or text</span>
                </div>
                <textarea
                    name="query"
                    id="query"
                    rows="5"
                    class="input bg-black/30 w-full resize-none font-mono text-white placeholder:text-[var(--text-muted)] focus:border-[var(--teal)] transition-all rounded-xl p-4 text-base"
                    placeholder="e.g.: Solve 2x + 5 = 17&#10;or: Find the vertex of the parabola y = x^2 - 4x + 3"
                ></textarea>
                @error('query')
                    <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sample Question Chips --}}
            <div class="space-y-2">
                <span class="text-xs font-mono text-[var(--text-muted)]">Sample problems (click one):</span>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="sample-chip text-xs bg-black/40 border border-[var(--border-strong)] hover:border-[var(--teal)] hover:text-[var(--teal)] text-[var(--text-secondary)] px-3 py-1.5 rounded-xl transition-all" data-prompt="2x + 5 = 17 tenglamani yeching">
                        2x + 5 = 17
                    </button>
                    <button type="button" class="sample-chip text-xs bg-black/40 border border-[var(--border-strong)] hover:border-[var(--teal)] hover:text-[var(--teal)] text-[var(--text-secondary)] px-3 py-1.5 rounded-xl transition-all" data-prompt="y = x^2 - 4x + 3 parabolasining cho'qqisini toping">
                        y = x² - 4x + 3
                    </button>
                    <button type="button" class="sample-chip text-xs bg-black/40 border border-[var(--border-strong)] hover:border-[var(--teal)] hover:text-[var(--teal)] text-[var(--text-secondary)] px-3 py-1.5 rounded-xl transition-all" data-prompt="2x + y = 7 va x - y = 2 tenglamalar sistemasini yeching">
                        System of equations
                    </button>
                    <button type="button" class="sample-chip text-xs bg-black/40 border border-[var(--border-strong)] hover:border-[var(--teal)] hover:text-[var(--teal)] text-[var(--text-secondary)] px-3 py-1.5 rounded-xl transition-all" data-prompt="y = 2^x + 1 ko'rsatkichli funksiyaning grafigini aniqlang">
                        y = 2ˣ + 1
                    </button>
                </div>
            </div>

            {{-- Image Upload & Preview Container --}}
            <div>
                <label class="block text-sm font-semibold text-white mb-2">
                    Or upload a photo <span class="text-[var(--text-muted)] font-normal">(PNG, JPG — max 5MB)</span>
                </label>

                <div class="flex items-center gap-4 flex-wrap">
                    <label class="inline-flex items-center gap-2 cursor-pointer bg-black/30 border border-[var(--border-strong)] hover:border-[var(--teal)] text-[var(--text-secondary)] hover:text-white px-5 py-3 rounded-xl transition-all">
                        <x-lucide-camera class="w-5 h-5 text-[var(--teal)]" />
                        <span class="text-sm font-medium">📷 Choose image</span>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                    </label>

                    <div id="imagePreviewWrapper" class="hidden items-center gap-3 bg-black/40 border border-[var(--teal)] px-3 py-1.5 rounded-xl">
                        <img id="imagePreviewThumb" src="" alt="Preview" class="w-9 h-9 object-cover rounded-lg">
                        <span id="imageFileName" class="text-xs font-mono text-white max-w-[160px] truncate"></span>
                        <button type="button" id="removeImgBtn" class="text-red-400 hover:text-red-300 font-bold px-1 text-base">&times;</button>
                    </div>
                </div>
                @error('image')
                    <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-[var(--border-subtle)]">
                <span class="text-xs text-[var(--text-muted)] flex items-center gap-1.5">
                    <x-lucide-zap class="w-4 h-4 text-[var(--gold)]" /> Integrated with the Desmos JS Engine
                </span>
                <button type="submit" id="solveBtn" class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[var(--gold)] to-[var(--gold-hover)] text-black font-extrabold rounded-xl shadow-lg shadow-[var(--gold-glow)] hover:brightness-110 transition-all">
                    <x-lucide-sparkles class="w-5 h-5" /> Solve
                </button>
            </div>
        </form>
    </div>

    {{-- Info Card --}}
    <div class="bg-[var(--bg-overlay)] rounded-2xl p-6 border border-[var(--border-subtle)] text-sm text-[var(--text-muted)] flex gap-4 items-start shadow-md">
        <x-lucide-info class="w-6 h-6 text-[var(--teal)] flex-shrink-0 mt-0.5" />
        <div class="space-y-1">
            <p class="text-white font-bold">How does Desmos AI help?</p>
            <p class="leading-relaxed">Using the Desmos graphing calculator correctly on the SAT Math test saves 2-3x the time. Our platform converts your problem into Desmos syntax, renders the graph, and teaches you fast solving techniques.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const queryInput = document.getElementById('query');
    const imageInput = document.getElementById('imageInput');
    const previewWrapper = document.getElementById('imagePreviewWrapper');
    const previewThumb = document.getElementById('imagePreviewThumb');
    const fileNameSpan = document.getElementById('imageFileName');
    const removeImgBtn = document.getElementById('removeImgBtn');

    // Sample Chips Handler
    document.querySelectorAll('.sample-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            queryInput.value = chip.dataset.prompt;
            queryInput.focus();
        });
    });

    // Image Upload & Preview Handler
    if (imageInput) {
        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (file) {
                fileNameSpan.textContent = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewThumb.src = e.target.result;
                    previewWrapper.classList.remove('hidden');
                    previewWrapper.classList.add('flex');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeImgBtn) {
        removeImgBtn.addEventListener('click', () => {
            imageInput.value = '';
            previewThumb.src = '';
            fileNameSpan.textContent = '';
            previewWrapper.classList.add('hidden');
            previewWrapper.classList.remove('flex');
        });
    }
});
</script>
@endsection
