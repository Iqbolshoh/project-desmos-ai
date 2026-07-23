@extends('layouts.dashboard')

@section('title', 'Tizim Analitikasi')

@section('content')
<div class="max-w-4xl mx-auto text-center py-20 space-y-4">
    <div class="w-24 h-24 mx-auto rounded-full bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)] mb-6 shadow-xl shadow-[var(--accent-glow)]">
        <x-lucide-activity class="w-12 h-12 text-[var(--accent)]" />
    </div>
    
    <h1 class="text-3xl font-bold text-white">Analitika</h1>
    <p class="text-[var(--text-secondary)] max-w-lg mx-auto">
        Joriy haftadagi faollik, eng qiyin o'zlashtirilayotgan mavzular va AI Tutor yuki kabi grafiklar tez orada qo'shiladi. (Demo)
    </p>
</div>
@endsection
