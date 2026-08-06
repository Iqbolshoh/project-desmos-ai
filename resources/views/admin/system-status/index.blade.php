@extends('layouts.dashboard')

@section('title', 'System Status')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
        <x-lucide-server-cog class="w-7 h-7 text-[var(--accent)]" />
        System Status & Infrastructure
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach([
            'database' => ['label' => "Database Connection", 'icon' => 'database'],
            'cache' => ['label' => 'Cache Driver', 'icon' => 'zap'],
            'queue' => ['label' => 'Queue Driver', 'icon' => 'list-checks'],
            'storage' => ['label' => 'Disk Storage', 'icon' => 'hard-drive'],
        ] as $key => $meta)
        @php $check = $checks[$key]; @endphp
        <div class="card p-5 flex items-center justify-between rounded-2xl border-[var(--border-strong)] bg-[var(--bg-overlay)] shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-[var(--accent-soft)] border border-[var(--accent-border)]">
                    <x-dynamic-component :component="'lucide-' . $meta['icon']" class="w-5 h-5 text-[var(--accent-hover)]" />
                </div>
                <div>
                    <p class="text-sm font-bold text-white">{{ $meta['label'] }}</p>
                    <p class="text-xs text-[var(--text-muted)] font-mono">{{ $check['detail'] }}</p>
                </div>
            </div>
            <span class="badge font-mono uppercase text-xs {{ $check['ok'] ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                {{ $check['ok'] ? 'OK' : 'Error' }}
            </span>
        </div>
        @endforeach
    </div>

    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-overlay)] rounded-2xl shadow-xl">
        <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-4 font-mono">Runtime Environment Overview</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-[var(--text-muted)]">PHP Version</p>
                <p class="text-white font-mono mt-1 font-semibold">{{ $info['php_version'] }}</p>
            </div>
            <div>
                <p class="text-[var(--text-muted)]">Laravel Version</p>
                <p class="text-white font-mono mt-1 font-semibold">{{ $info['laravel_version'] }}</p>
            </div>
            <div>
                <p class="text-[var(--text-muted)]">Environment</p>
                <p class="text-white font-mono mt-1 font-semibold">{{ $info['environment'] }}</p>
            </div>
            <div>
                <p class="text-[var(--text-muted)]">Debug Mode</p>
                <p class="text-white font-mono mt-1 font-semibold">{{ $info['debug_mode'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
