@extends('layouts.dashboard')

@section('title', 'Tizim Holati')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
        <x-lucide-server-cog class="w-7 h-7 text-[var(--accent)]" />
        Tizim Holati
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach([
            'database' => ['label' => "Ma'lumotlar bazasi", 'icon' => 'database'],
            'cache' => ['label' => 'Cache', 'icon' => 'zap'],
            'queue' => ['label' => 'Queue', 'icon' => 'list-checks'],
            'storage' => ['label' => 'Disk', 'icon' => 'hard-drive'],
        ] as $key => $meta)
        @php $check = $checks[$key]; @endphp
        <div class="card p-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--accent-soft); border: 1px solid var(--accent-border);">
                    <x-dynamic-component :component="'lucide-' . $meta['icon']" class="w-5 h-5" style="color: var(--accent-hover);" />
                </div>
                <div>
                    <p class="text-sm font-bold text-white">{{ $meta['label'] }}</p>
                    <p class="text-xs text-[var(--text-muted)] font-mono">{{ $check['detail'] }}</p>
                </div>
            </div>
            <span class="badge {{ $check['ok'] ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                {{ $check['ok'] ? 'OK' : 'Xatolik' }}
            </span>
        </div>
        @endforeach
    </div>

    <div class="card p-6">
        <h3 class="text-sm font-bold uppercase tracking-widest text-[var(--text-muted)] mb-4">Muhit ma'lumotlari</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-[var(--text-muted)]">PHP versiyasi</p>
                <p class="text-white font-mono mt-1">{{ $info['php_version'] }}</p>
            </div>
            <div>
                <p class="text-[var(--text-muted)]">Laravel versiyasi</p>
                <p class="text-white font-mono mt-1">{{ $info['laravel_version'] }}</p>
            </div>
            <div>
                <p class="text-[var(--text-muted)]">Muhit</p>
                <p class="text-white font-mono mt-1">{{ $info['environment'] }}</p>
            </div>
            <div>
                <p class="text-[var(--text-muted)]">Debug rejimi</p>
                <p class="text-white font-mono mt-1">{{ $info['debug_mode'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
