@extends('layouts.dashboard')

@section('title', 'Boshqaruv paneli')
@section('breadcrumb', 'Desmos AI')
@section('header_title', 'Boshqaruv paneli')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    {{-- Welcome card --}}
    <div class="relative overflow-hidden card border-[var(--accent-border)] bg-gradient-to-br from-[var(--bg-raised)] to-black rounded-3xl p-8 sm:p-10">
        <div class="absolute inset-0 bg-gradient-to-br from-[var(--accent-soft)] to-transparent pointer-events-none"></div>
        <div class="absolute -right-20 -bottom-20 opacity-5">
            <x-lucide-layout-dashboard class="w-96 h-96 text-[var(--accent)]" />
        </div>
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div class="w-20 h-20 rounded-3xl flex items-center justify-center text-white font-black text-3xl flex-shrink-0 shadow-2xl shadow-[var(--accent-glow)] border border-[var(--accent-border)]"
                 style="background: linear-gradient(135deg, var(--accent), var(--accent-alt));">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-extrabold text-white leading-tight mb-2">
                    Xush kelibsiz, {{ auth()->user()->name ?? 'Admin' }}!
                </h2>
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-overlay)] px-3 py-1 rounded-lg border border-[var(--border-subtle)]">
                        <x-lucide-mail class="w-3.5 h-3.5 inline mr-1 text-[var(--text-muted)]" />
                        {{ auth()->user()->email ?? '' }}
                    </p>
                    @if(auth()->user()->getRoleNames()->isNotEmpty())
                    <span class="px-3 py-1 bg-[var(--accent-soft)] text-[var(--accent-hover)] text-xs font-bold rounded-lg border border-[var(--accent-border)] uppercase tracking-wider">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="text-right flex-shrink-0 bg-black/40 p-4 rounded-2xl border border-[var(--border-strong)] backdrop-blur-md">
                <div class="flex items-center gap-2 text-[var(--text-muted)] mb-1">
                    <x-lucide-calendar class="w-4 h-4" />
                    <p class="text-sm font-bold">{{ now()->format('d M, Y') }}</p>
                </div>
                <div class="flex items-center gap-2 text-white">
                    <x-lucide-clock class="w-4 h-4 text-[var(--accent)]" />
                    <p class="text-2xl font-mono font-bold">{{ now()->format('H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Student gamification widgets --}}
    @if ($studentProfile)
    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <h3 class="text-lg font-extrabold text-white">SAT Tayyorgarligi</h3>
            <div class="h-px flex-1 bg-gradient-to-r from-[var(--border-strong)] to-transparent"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @include('dashboard.partials.xp-widget')
            @include('dashboard.partials.streak-widget')
            @include('dashboard.partials.goal-widget')
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            @include('dashboard.partials.recent-activity')
            @include('dashboard.partials.weak-strong-topics')
        </div>

        <div class="mt-6">
            @include('dashboard.partials.achievements-widget')
        </div>
    </div>
    @endif

    {{-- Platform stats (Admin) --}}
    @canany(['users.view', 'roles.view'])
    <div class="space-y-6 mt-12">
        <div class="flex items-center gap-3">
            <h3 class="text-lg font-extrabold text-white">Platforma boshqaruvi</h3>
            <div class="h-px flex-1 bg-gradient-to-r from-[var(--border-strong)] to-transparent"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @can('users.view')
            <div class="card p-6 border-[var(--border-strong)] rounded-2xl hover:border-[var(--accent-border)] transition-all duration-300 group hover:-translate-y-1 hover:shadow-xl hover:shadow-[var(--accent-glow)] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--accent-soft)] rounded-bl-full opacity-20 -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] group-hover:text-[var(--accent-hover)] transition-colors">Foydalanuvchilar</p>
                        <p class="mt-2 text-4xl font-extrabold text-white">{{ \App\Models\User::count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[var(--accent-soft)] border border-[var(--accent-border)] group-hover:scale-110 transition-transform">
                        <x-lucide-users class="w-6 h-6 text-[var(--accent)]" />
                    </div>
                </div>
                <a href="{{ route('users.index') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-[var(--accent-hover)] hover:text-white transition-colors relative z-10">
                    Barchasini ko'rish <x-lucide-arrow-right class="w-4 h-4" />
                </a>
            </div>
            @endcan

            @can('roles.view')
            <div class="card p-6 border-[var(--border-strong)] rounded-2xl hover:border-indigo-500/30 transition-all duration-300 group hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-bl-full opacity-20 -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)] group-hover:text-indigo-400 transition-colors">Rollar</p>
                        <p class="mt-2 text-4xl font-extrabold text-white">{{ \Spatie\Permission\Models\Role::count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-indigo-500/10 border border-indigo-500/20 group-hover:scale-110 transition-transform">
                        <x-lucide-shield-check class="w-6 h-6 text-indigo-400" />
                    </div>
                </div>
                <a href="{{ route('roles.index') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-indigo-400 hover:text-white transition-colors relative z-10">
                    Barchasini ko'rish <x-lucide-arrow-right class="w-4 h-4" />
                </a>
            </div>
            @endcan

            <div class="card p-6 border-[var(--border-strong)] rounded-2xl relative overflow-hidden bg-gradient-to-br from-[var(--bg-raised)] to-black group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--gold-soft)] rounded-bl-full opacity-10 -mr-10 -mt-10"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Tizim holati</p>
                        <p class="mt-2 text-xl font-extrabold text-white">Desmos AI v1.0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden border border-[var(--border-strong)] bg-white/5 p-2 backdrop-blur-md shadow-lg group-hover:border-[var(--gold-border)] transition-colors">
                        <img src="{{ asset('/images/logo.png') }}" alt="Desmos AI" class="w-full h-full object-contain filter drop-shadow-md">
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 text-sm text-[var(--text-secondary)] relative z-10">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    Laravel {{ app()->version() }} • Barcha tizimlar normal
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="card p-6 border-[var(--border-strong)] rounded-2xl mt-6 bg-[var(--bg-overlay)]">
            <h3 class="text-sm font-bold text-white mb-5 uppercase tracking-wider flex items-center gap-2">
                <x-lucide-zap class="w-4 h-4 text-[var(--accent)]" /> Tezkor amallar
            </h3>
            <div class="flex flex-wrap gap-3">
                @can('users.create')
                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-alt)] text-white font-bold rounded-xl shadow-lg hover:shadow-[var(--accent-glow)] transition-all hover:-translate-y-0.5">
                    <x-lucide-user-plus class="w-4 h-4" /> Foydalanuvchi qo'shish
                </a>
                @endcan
                @can('roles.create')
                <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 font-bold rounded-xl hover:bg-indigo-500 hover:text-white transition-all">
                    <x-lucide-shield-plus class="w-4 h-4" /> Rol qo'shish
                </a>
                @endcan
                @can('roles.view')
                <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-black/20 border border-[var(--border-subtle)] text-[var(--text-secondary)] font-bold rounded-xl hover:text-white hover:border-[var(--border-strong)] transition-all">
                    <x-lucide-shield-check class="w-4 h-4" /> Rollarni boshqarish
                </a>
                @endcan
            </div>
        </div>
    </div>
    @endcanany

</div>
@endsection
