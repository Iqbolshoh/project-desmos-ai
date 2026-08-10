<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b0d12" id="theme-color-meta">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Apply saved theme ASAP to prevent flash of wrong theme --}}
    <script>
        (function() {
            if (localStorage.getItem('desmos_ai_theme') === 'light') {
                document.documentElement.classList.add('light');
                document.addEventListener('DOMContentLoaded', function() {
                    var m = document.getElementById('theme-color-meta');
                    if (m) m.content = '#f0f2f7';
                });
            }
            // Pre-collapse the sidebar before first paint, for the same
            // reason as the theme above: Alpine only reads this after its
            // deferred script loads, so the sidebar would render expanded
            // and then visibly snap shut on every page load.
            if (localStorage.getItem('desmos_ai_sidebar') === 'closed') {
                document.documentElement.classList.add('sidebar-pre-init');
            }
        })();
    </script>

    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>

    {{-- Font: Inter — clean professional UI font for education platforms --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
        }

        body {
            background: var(--bg-base);
            color: var(--text-primary);
        }

        .font-mono {
            font-family: 'JetBrains Mono', ui-monospace, monospace;
        }

        /* ---------- Scrollbar ---------- */
        .scrollbar-none {
            scrollbar-width: none;
        }

        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }

        .scroll-area {
            scrollbar-width: thin;
            scrollbar-color: #252f42 transparent;
        }

        .scroll-area::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .scroll-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-area::-webkit-scrollbar-thumb {
            background: #252f42;
            border-radius: 99px;
            cursor: pointer;
        }

        .scroll-area::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        /* ---------- Navigation link ---------- */
        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.875rem;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: color .2s, background .2s;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-link .nav-icon {
            width: 1.15rem;
            height: 1.15rem;
            color: var(--text-muted);
            transition: color .2s;
            flex-shrink: 0;
        }

        .nav-link:hover .nav-icon {
            color: var(--text-secondary);
        }

        /* Active state -- glowing purple/blue line on the left */
        .nav-link.active {
            color: #fff;
            font-weight: 600;
            background: linear-gradient(90deg, var(--accent-soft), transparent 85%);
        }

        .nav-link.active .nav-icon {
            color: var(--accent-hover);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 50%;
            width: 3px;
            height: 60%;
            transform: translateY(-50%);
            background: linear-gradient(to bottom, var(--accent-hover), var(--accent-alt));
            border-radius: 0 99px 99px 0;
            box-shadow: 0 0 12px 1px var(--accent-glow);
        }

        .nav-heading {
            padding: 0 0.875rem;
            margin-bottom: 0.625rem;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent-hover);
        }

        /* Light mode needs the deeper shade: --accent-hover is tuned for a dark
           sidebar and washes out against a pale one. */
        html.light .nav-heading {
            color: var(--accent-deep);
        }

        /* ---------- Reusable components ---------- */
        .card {
            background: var(--bg-raised);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(12px);
        }

        .card-hover {
            cursor: pointer;
            transition: border-color .25s, transform .25s var(--ease-out), box-shadow .25s;
        }

        .card-hover:hover {
            border-color: var(--border-strong);
            transform: translateY(-2px);
            box-shadow: 0 12px 32px -12px rgba(0, 0, 0, 0.5);
        }

        .btn-primary,
        .btn-secondary,
        .btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.125rem;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all .2s var(--ease-out);
        }

        .btn-primary {
            color: #ffffff;
            background: linear-gradient(135deg, var(--accent), var(--accent-alt));
            box-shadow: 0 4px 16px -4px var(--accent-glow), inset 0 1px 0 rgba(255, 255, 255, 0.15);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--accent-hover), var(--accent-alt));
            transform: translateY(-1px);
            box-shadow: 0 6px 24px -4px var(--accent-glow), inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .btn-primary:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-gold {
            color: #1a1206;
            background: linear-gradient(135deg, var(--gold), var(--gold-alt));
            box-shadow: 0 4px 16px -4px var(--gold-glow), inset 0 1px 0 rgba(255, 255, 255, 0.25);
            font-weight: 700;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, var(--gold-hover), var(--gold-alt));
            transform: translateY(-1px);
            box-shadow: 0 6px 24px -4px var(--gold-glow), inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .btn-gold:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-secondary {
            color: var(--text-primary);
            background: var(--bg-overlay);
            border: 1px solid var(--border-strong);
        }

        .btn-secondary:hover {
            background: #2d3a52;
        }

        .btn-ghost {
            color: var(--text-secondary);
        }

        .btn-ghost:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .badge-accent {
            background: var(--accent-soft);
            color: var(--accent-hover);
            border: 1px solid var(--accent-border);
        }

        .badge-success {
            background: rgba(52, 211, 153, 0.1);
            color: var(--success);
            border: 1px solid rgba(52, 211, 153, 0.25);
        }

        .badge-gold {
            background: var(--gold-soft);
            color: var(--gold-hover);
            border: 1px solid var(--gold-border);
        }

        .input {
            width: 100%;
            padding: 0.7rem 1rem;
            background: var(--bg-surface);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            color: var(--text-primary);
            transition: border-color .2s, box-shadow .2s;
        }

        .input::placeholder {
            color: var(--text-muted);
        }

        .input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        /* ---------- Page enter animation ---------- */
        @keyframes page-enter {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .page-enter {
            /* Opacity only, and short. A translateY here slid the whole content
               column on every single navigation, which is what people report as
               the page jumping; a long fade reads as a flash for the same reason. */
            animation: page-enter .18s var(--ease-out) both;
        }

        @media (prefers-reduced-motion: reduce) {
            .page-enter {
                animation: none;
            }
        }

        /* Sidebar state applied before first paint. Alpine reads localStorage only
           after its deferred script loads, so without this the sidebar renders
           expanded and then snaps shut on every page load. x-init removes the
           class once Alpine has mounted and taken over. */
        @media (min-width: 768px) {
            html.sidebar-pre-init aside {
                width: 4rem;
                overflow: hidden;
                transition: none !important;
            }

            html.sidebar-pre-init aside .sidebar-label,
            html.sidebar-pre-init aside .nav-heading,
            html.sidebar-pre-init aside .sidebar-logo-text,
            html.sidebar-pre-init aside .sidebar-user-info,
            html.sidebar-pre-init aside .sidebar-logout-btn {
                display: none;
            }

            html.sidebar-pre-init aside .nav-link {
                justify-content: center;
                padding: 0.65rem;
            }

            html.sidebar-pre-init aside .nav-link.active::before {
                left: 0;
            }

            html.sidebar-pre-init aside .sidebar-logo-container {
                padding-left: 0;
                padding-right: 0;
                justify-content: center;
            }

            html.sidebar-pre-init aside .sidebar-logo-link {
                gap: 0;
                justify-content: center;
            }

            html.sidebar-pre-init aside .sidebar-user-card-inner {
                gap: 0;
                justify-content: center;
                padding: 0.5rem;
            }

            html.sidebar-pre-init aside .sidebar-user-section {
                padding: 0.5rem;
            }
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateX(16px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-in {
            animation: toast-in .35s var(--ease-out) both;
        }

        @keyframes toast-shrink {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        /* ---------- Keyboard focus ---------- */
        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* ---------- Reduced motion ---------- */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        .input.pl-7 { padding-left: 1.75rem; }
        .input.pl-9 { padding-left: 2.25rem; }
        .input.pl-10 { padding-left: 2.5rem; }
        .input.pl-11 { padding-left: 2.75rem; }

        button:not([disabled]),
        [role="button"]:not([disabled]),
        summary,
        label[for],
        label:has(input) {
            cursor: pointer;
        }

        .h-dvh {
            height: 100vh;
            height: 100dvh;
        }

        @media (max-width: 767px) {
            .sidebar-overlay-open {
                overflow: hidden;
            }
            .dropdown-mobile-safe {
                max-width: calc(100vw - 1.5rem);
            }
        }

        html.light body {
            background: var(--bg-base);
            color: var(--text-primary);
        }

        html.light .text-white {
            color: var(--text-primary) !important;
        }

        html.light .hover\:text-white:hover {
            color: var(--text-primary) !important;
        }

        html.light .btn-primary {
            color: #ffffff !important;
        }

        html.light .bg-\[var\(--accent\)\].text-white,
        html.light .bg-\[var\(--accent\)\] .text-white,
        html.light .bg-\[var\(--accent-hover\)\].text-white,
        html.light .bg-\[var\(--accent-hover\)\] .text-white {
            color: #ffffff !important;
        }

        html.light [style*="linear-gradient"] .text-white,
        html.light [style*="linear-gradient"].text-white {
            color: #ffffff !important;
        }

        html.light .text-gray-100,
        html.light .text-gray-200 { color: #374151 !important; }
        html.light .text-gray-300 { color: #4b5563 !important; }
        html.light .text-gray-400 { color: #6b7280 !important; }

        html.light .bg-white\/5,
        html.light .bg-white\/\[0\.03\],
        html.light .bg-white\/\[0\.04\],
        html.light .bg-white\/\[0\.08\] {
            background-color: rgba(0, 0, 0, 0.04) !important;
        }

        html.light .hover\:bg-white\/5:hover,
        html.light .hover\:bg-white\/\[0\.02\]:hover,
        html.light .hover\:bg-white\/\[0\.03\]:hover,
        html.light .hover\:bg-white\/\[0\.04\]:hover,
        html.light .hover\:bg-white\/10:hover,
        html.light .hover\:bg-white\/\[0\.05\]:hover {
            background-color: rgba(0, 0, 0, 0.05) !important;
        }

        html.light .border-white\/5 { border-color: rgba(0, 0, 0, 0.06) !important; }
        html.light .border-white\/10 { border-color: rgba(0, 0, 0, 0.10) !important; }
        html.light .border-white\/20 { border-color: rgba(0, 0, 0, 0.15) !important; }

        html.light .scroll-area { scrollbar-color: #cbd5e1 transparent; }
        html.light .scroll-area::-webkit-scrollbar-thumb { background: #cbd5e1; }
        html.light .scroll-area::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        html.light .nav-link:hover { background: rgba(0, 0, 0, 0.05); }
        html.light .nav-link.active { color: var(--accent-deep); }

        html.light .btn-secondary:hover { background: #e2e8f0; }
        html.light .btn-ghost:hover { color: var(--text-primary); background: rgba(0, 0, 0, 0.05); }
        html.light .card-hover:hover { box-shadow: 0 12px 32px -12px rgba(0, 0, 0, 0.12); }
        html.light thead tr { background: rgba(0, 0, 0, 0.025) !important; }

        html.light input.text-white,
        html.light select.text-white,
        html.light textarea.text-white { color: var(--text-primary) !important; }

        html.light .bg-black\/70 { background-color: rgba(0, 0, 0, 0.50) !important; }
        html.light .bg-black\/60 { background-color: rgba(0, 0, 0, 0.40) !important; }

        html.light .shadow-2xl { box-shadow: 0 8px 40px rgba(0, 0, 0, 0.10); }

        html.light header button:hover,
        html.light header a:hover { background-color: rgba(0, 0, 0, 0.05) !important; }

        html.light .bg-deco-purple { background: rgba(124, 58, 237, 0.05); }
        html.light .bg-deco-blue { background: rgba(37, 99, 235, 0.06); }

        .theme-icon-light { display: none; }
        html.light .theme-icon-dark { display: none; }
        html.light .theme-icon-light { display: block; }

        @media (min-width: 768px) {
            aside { transition: width 0.3s var(--ease-out); }
            aside.sidebar-collapsed { width: 4rem; overflow: hidden; }
            aside.sidebar-collapsed .sidebar-label { display: none; }
            aside.sidebar-collapsed .nav-heading { display: none; }
            aside.sidebar-collapsed .nav-link { justify-content: center; padding: 0.65rem; }
            aside.sidebar-collapsed .nav-link.active::before { left: 0; }
            aside.sidebar-collapsed .sidebar-logo-container { padding-left: 0; padding-right: 0; justify-content: center; }
            aside.sidebar-collapsed .sidebar-logo-link { gap: 0; justify-content: center; }
            aside.sidebar-collapsed .sidebar-logo-text { display: none; }
            aside.sidebar-collapsed .sidebar-user-info { display: none; }
            aside.sidebar-collapsed .sidebar-logout-btn { display: none; }
            aside.sidebar-collapsed .sidebar-user-card-inner { gap: 0; justify-content: center; padding: 0.5rem; }
            aside.sidebar-collapsed .sidebar-user-section { padding: 0.5rem; }
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased selection:bg-[var(--accent)] selection:text-white overflow-hidden">

    <div x-data="{
            mobileMenuOpen: false,
            logoutModalOpen: false,
            notificationsOpen: false,
            profileOpen: false,
            sidebarOpen: localStorage.getItem('desmos_ai_sidebar') !== 'closed',
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                localStorage.setItem('desmos_ai_sidebar', this.sidebarOpen ? 'open' : 'closed');
            }
        }"
        x-init="$nextTick(() => document.documentElement.classList.remove('sidebar-pre-init'))"
        class="flex h-dvh w-full relative"
        @keydown.escape.window="mobileMenuOpen = false; logoutModalOpen = false; notificationsOpen = false; profileOpen = false">

        {{-- LOGOUT MODAL --}}
        <div x-show="logoutModalOpen" x-cloak class="fixed inset-0 z-[70]" role="dialog" aria-modal="true" aria-labelledby="logout-title" style="display: none;">
            <div x-show="logoutModalOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/70 backdrop-blur-sm cursor-pointer"></div>

            <div class="flex min-h-screen items-center justify-center p-4">
                <div x-show="logoutModalOpen" @click.away="logoutModalOpen = false" @keydown.escape.window="logoutModalOpen = false"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="relative w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-raised)] border border-[var(--border-strong)] shadow-2xl p-7 cursor-default">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--accent-soft)] border border-[var(--accent-border)]">
                        <x-lucide-log-out class="h-6 w-6 text-[var(--accent-hover)]" />
                    </div>

                    <div class="mt-5 text-center">
                        <h3 id="logout-title" class="text-xl font-bold text-white tracking-tight">Confirm Logout</h3>
                        <p class="mt-2 text-sm text-[var(--text-secondary)] leading-relaxed">
                            Are you sure you want to log out of your {{ config('app.name') }} account?
                        </p>
                    </div>

                    <div class="mt-7 flex flex-col sm:flex-row gap-3">
                        <button type="button" @click="logoutModalOpen = false" class="btn-secondary flex-1 cursor-pointer">
                            Cancel
                        </button>
                        <form action="{{ route('logout') ?? '#' }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="btn-primary w-full cursor-pointer">
                                <x-lucide-log-out class="w-4 h-4" />
                                Yes, Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MOBILE MENU BACKGROUND --}}
        <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false" x-transition.opacity
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 md:hidden cursor-pointer" style="display: none;"></div>

        {{-- SIDEBAR --}}
        <aside class="w-[var(--sidebar-w)] bg-[var(--bg-surface)] border-r border-[var(--border-subtle)] flex flex-col z-50 fixed inset-y-0 left-0 md:relative md:translate-x-0 transition-transform duration-300 ease-[var(--ease-out)] flex-shrink-0"
            :class="{
                'translate-x-0 shadow-2xl shadow-black/50': mobileMenuOpen,
                '-translate-x-full': !mobileMenuOpen,
                'sidebar-collapsed': !sidebarOpen,
            }">

            {{-- Logo --}}
            <div class="sidebar-logo-container h-[var(--header-h)] flex items-center px-5 border-b border-[var(--border-subtle)] flex-shrink-0 transition-all duration-300">
                <a href="{{ route('dashboard.index') }}" class="sidebar-logo-link flex items-center gap-3 group min-w-0 cursor-pointer transition-all duration-300">
                    <div class="w-9 h-9 flex items-center justify-center transition-transform duration-300 group-hover:scale-105 overflow-hidden flex-shrink-0 border border-[var(--border-strong)] rounded-xl bg-white/5 p-0.5 shadow-sm">
                        <img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}" class="w-full h-full object-contain">
                    </div>
                    <span class="sidebar-logo-text font-extrabold text-lg tracking-tight truncate" style="background: linear-gradient(135deg, var(--accent-hover), var(--accent-alt)); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ config('app.name') }} <span class="text-xs font-mono text-[var(--gold)]">v2.1</span>
                    </span>
                </a>
                <button @click="mobileMenuOpen = false" class="md:hidden ml-auto text-[var(--text-muted)] hover:text-white p-2 rounded-lg hover:bg-white/5 transition-colors cursor-pointer" aria-label="Close menu">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            {{-- Navigation menu --}}
            <nav class="flex-1 px-4 py-6 space-y-7 overflow-y-auto scroll-area">

                {{-- Dashboard --}}
                <div>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard.index') }}"
                            title="Dashboard"
                            class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                            <x-lucide-layout-dashboard class="nav-icon" />
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                    </div>
                </div>

                {{-- Student Portal --}}
                <div>
                    <h3 class="nav-heading">Student Portal</h3>
                    <div class="space-y-1">
                        <a href="{{ route('tutor.index') }}"
                            title="AI Tutor"
                            class="nav-link {{ request()->routeIs('tutor.*') ? 'active' : '' }}">
                            <x-lucide-bot class="nav-icon" />
                            <span class="sidebar-label">AI Tutor</span>
                        </a>
                        <a href="{{ route('history.index') }}"
                            title="History"
                            class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}">
                            <x-lucide-history class="nav-icon" />
                            <span class="sidebar-label">History</span>
                        </a>
                        <a href="{{ route('diagnostic.start') }}"
                            title="Diagnostic Test"
                            class="nav-link {{ request()->routeIs('diagnostic.*') ? 'active' : '' }}">
                            <x-lucide-target class="nav-icon" />
                            <span class="sidebar-label">Diagnostic Test</span>
                        </a>
                        <a href="{{ route('roadmap.show') }}"
                            title="Study Roadmap"
                            class="nav-link {{ request()->routeIs('roadmap.*') ? 'active' : '' }}">
                            <x-lucide-map class="nav-icon" />
                            <span class="sidebar-label">Study Roadmap</span>
                        </a>
                    </div>
                </div>

                {{-- Practice & Chat --}}
                <div>
                    <h3 class="nav-heading">Practice & AI Chat</h3>
                    <div class="space-y-1">
                        <a href="{{ route('practice.index') }}"
                            title="Practice Bank"
                            class="nav-link {{ request()->routeIs('practice.*') ? 'active' : '' }}">
                            <x-lucide-dumbbell class="nav-icon" />
                            <span class="sidebar-label">Practice Bank</span>
                        </a>
                        <a href="{{ route('chat.index') }}"
                            title="AI Chat Tutor"
                            class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                            <x-lucide-messages-square class="nav-icon" />
                            <span class="sidebar-label">AI Chat Tutor</span>
                        </a>
                    </div>
                </div>

                {{-- Competition --}}
                <div>
                    <h3 class="nav-heading">Competition</h3>
                    <div class="space-y-1">
                        <a href="{{ route('leaderboard.index') }}"
                            title="Leaderboard"
                            class="nav-link {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                            <x-lucide-trophy class="nav-icon" />
                            <span class="sidebar-label">Leaderboard</span>
                        </a>
                    </div>
                </div>

                {{-- Administration --}}
                @canany(['roles.view', 'users.view'])
                <div>
                    <h3 class="nav-heading">Administration</h3>
                    <div class="space-y-1">
                        @can('users.view')
                        <a href="{{ route('admin.questions.index') }}"
                            title="Questions Bank"
                            class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                            <x-lucide-database class="nav-icon" />
                            <span class="sidebar-label">Questions</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}"
                            title="Reports"
                            class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <x-lucide-file-bar-chart class="nav-icon" />
                            <span class="sidebar-label">Reports</span>
                        </a>
                        <a href="{{ route('admin.analytics.index') }}"
                            title="Analytics"
                            class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                            <x-lucide-activity class="nav-icon" />
                            <span class="sidebar-label">Analytics</span>
                        </a>
                        <a href="{{ route('admin.system-status.index') }}"
                            title="System Status"
                            class="nav-link {{ request()->routeIs('admin.system-status.*') ? 'active' : '' }}">
                            <x-lucide-server-cog class="nav-icon" />
                            <span class="sidebar-label">System Status</span>
                        </a>
                        <a href="{{ route('users.index') }}"
                            title="Users"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <x-lucide-user-cog class="nav-icon" />
                            <span class="sidebar-label">Users</span>
                        </a>
                        @endcan
                        @can('roles.view')
                        <a href="{{ route('roles.index') }}"
                            title="Roles"
                            class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <x-lucide-shield-check class="nav-icon" />
                            <span class="sidebar-label">Roles</span>
                        </a>
                        @endcan
                    </div>
                </div>
                @endcanany

            </nav>

            {{-- User card --}}
            <div class="sidebar-user-section p-4 border-t border-[var(--border-subtle)] flex-shrink-0 transition-all duration-300">
                <div class="sidebar-user-card-inner flex items-center gap-3 p-2.5 rounded-[var(--radius-md)] bg-[var(--bg-raised)] border border-[var(--border-subtle)] hover:bg-[var(--bg-overlay)] transition-all duration-300">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, var(--accent-hover), var(--accent-alt));">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info min-w-0 flex-1">
                        <p class="text-sm font-semibold text-white truncate leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-[var(--text-muted)] truncate">{{ auth()->user()->email ?? 'admin@desmos.ai' }}</p>
                    </div>
                    <button @click="logoutModalOpen = true" type="button"
                        class="sidebar-logout-btn p-2 rounded-lg text-[var(--text-muted)] hover:text-[var(--accent-hover)] hover:bg-[var(--accent-soft)] transition-colors flex-shrink-0 cursor-pointer"
                        aria-label="Log Out" title="Log Out">
                        <x-lucide-log-out class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col overflow-hidden relative min-w-0">

            {{-- Background decoration blobs --}}
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none" aria-hidden="true">
                <div class="absolute top-[-15%] left-[-5%] w-[28rem] h-[28rem] rounded-full blur-[120px] bg-deco-blue" style="background: rgba(59, 130, 246, 0.07);"></div>
                <div class="absolute bottom-[-15%] right-[-5%] w-[32rem] h-[32rem] rounded-full blur-[140px] bg-deco-purple" style="background: rgba(129, 140, 248, 0.05);"></div>
            </div>

            {{-- Header --}}
            <header class="h-[var(--header-h)] flex items-center justify-between gap-4 px-4 sm:px-7 bg-[var(--bg-base)]/80 backdrop-blur-xl border-b border-[var(--border-subtle)] z-30 relative flex-shrink-0">

                <div class="flex items-center gap-3 min-w-0">
                    <button @click="mobileMenuOpen = true" class="md:hidden text-[var(--text-secondary)] hover:text-white p-2 rounded-xl hover:bg-white/5 transition-colors cursor-pointer" aria-label="Open menu">
                        <x-lucide-menu class="w-5 h-5" />
                    </button>
                    <button @click="toggleSidebar()"
                        class="hidden md:flex items-center justify-center p-2 rounded-xl text-[var(--text-secondary)] hover:text-white hover:bg-white/5 border border-transparent hover:border-[var(--border-subtle)] transition-colors cursor-pointer flex-shrink-0"
                        :title="sidebarOpen ? 'Collapse Sidebar' : 'Expand Sidebar'"
                        aria-label="Toggle sidebar">
                        <x-lucide-menu class="w-5 h-5" />
                    </button>

                    <div class="min-w-0 cursor-default">
                        @hasSection('breadcrumb')
                        <p class="text-[0.68rem] font-semibold uppercase tracking-widest text-[var(--text-muted)] mb-0.5 truncate">
                            @yield('breadcrumb')
                        </p>
                        @endif
                        <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight truncate leading-tight">
                            @yield('header_title', 'Dashboard')
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">

                    <div class="hidden sm:flex items-center gap-3">
                        @yield('header_actions')
                    </div>

                    {{-- Notifications --}}
                    <div class="relative">
                        <button @click="notificationsOpen = !notificationsOpen" @click.away="notificationsOpen = false"
                            class="relative p-2.5 text-[var(--text-secondary)] hover:text-white hover:bg-white/5 rounded-xl border border-transparent hover:border-[var(--border-subtle)] transition-colors cursor-pointer"
                            aria-label="Notifications">
                            <x-lucide-bell class="w-5 h-5" />
                            <span class="absolute top-2 right-2 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-60" style="background: var(--accent-alt);"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 ring-2 ring-[var(--bg-base)]" style="background: var(--accent-alt);"></span>
                            </span>
                        </button>

                        <div x-show="notificationsOpen" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-[min(22rem,calc(100vw-1.5rem))] bg-[var(--bg-raised)] border border-[var(--border-strong)] rounded-[var(--radius-lg)] shadow-2xl shadow-black/60 z-50 overflow-hidden cursor-default"
                            style="display: none;">

                            <div class="px-4 py-3.5 border-b border-[var(--border-subtle)] flex justify-between items-center">
                                <h3 class="text-sm font-bold text-white">Notifications</h3>
                                <span class="badge badge-accent cursor-pointer">2 new</span>
                            </div>

                            <div class="max-h-80 overflow-y-auto scroll-area">
                                <a href="#" class="flex items-start gap-3 px-4 py-3.5 hover:bg-white/[0.03] transition-colors border-b border-[var(--border-subtle)] group cursor-pointer">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                        <x-lucide-check-circle class="w-4 h-4 text-emerald-400" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm text-gray-200 font-medium group-hover:text-white transition-colors">Desmos Engine Updated</p>
                                        <p class="text-xs text-[var(--text-muted)] mt-1">Live interactive Desmos Graphing API v1.9 active.</p>
                                        <p class="text-[0.68rem] text-[var(--text-muted)] mt-1.5 font-mono">5 mins ago</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start gap-3 px-4 py-3.5 hover:bg-white/[0.03] transition-colors group cursor-pointer">
                                    <div class="w-9 h-9 rounded-xl border flex items-center justify-center flex-shrink-0" style="background: var(--accent-soft); border-color: var(--accent-border);">
                                        <x-lucide-info class="w-4 h-4" style="color: var(--accent-alt);" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm text-gray-200 font-medium group-hover:text-white transition-colors">System Update v2.1</p>
                                        <p class="text-xs text-[var(--text-muted)] mt-1">New English language & performance enhancements live.</p>
                                        <p class="text-[0.68rem] text-[var(--text-muted)] mt-1.5 font-mono">1 hour ago</p>
                                    </div>
                                </a>
                            </div>

                            <div class="px-4 py-2.5 border-t border-[var(--border-subtle)] bg-[var(--bg-surface)]">
                                <a href="#" class="text-xs font-semibold text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors cursor-pointer">View all</a>
                            </div>
                        </div>
                    </div>

                    {{-- Theme Toggle --}}
                    <button onclick="toggleTheme()"
                        class="p-2.5 text-[var(--text-secondary)] hover:text-[var(--text-primary)] rounded-xl border border-transparent hover:border-[var(--border-subtle)] transition-colors cursor-pointer"
                        aria-label="Toggle light/dark mode"
                        title="Toggle light/dark mode">
                        <x-lucide-sun class="w-5 h-5 theme-icon-dark" />
                        <x-lucide-moon class="w-5 h-5 theme-icon-light" />
                    </button>

                    {{-- Profile --}}
                    <div class="relative">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                            class="flex items-center gap-2.5 p-1.5 pr-2.5 rounded-xl hover:bg-white/5 border border-transparent hover:border-[var(--border-subtle)] transition-colors cursor-pointer"
                            aria-label="Profile menu">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md" style="background: linear-gradient(135deg, var(--accent-hover), var(--accent-alt));">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <x-lucide-chevron-down class="hidden md:block w-3.5 h-3.5 text-[var(--text-muted)]" />
                        </button>

                        <div x-show="profileOpen" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-[min(14rem,calc(100vw-1.5rem))] bg-[var(--bg-raised)] border border-[var(--border-strong)] rounded-[var(--radius-lg)] shadow-2xl shadow-black/60 py-1.5 z-50 cursor-default"
                            style="display: none;">

                            <div class="px-4 py-3 border-b border-[var(--border-subtle)]">
                                <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-[var(--text-muted)] truncate mt-0.5">{{ auth()->user()->email ?? 'admin@desmos.ai' }}</p>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[var(--text-secondary)] hover:bg-white/[0.04] hover:text-white font-medium transition-colors cursor-pointer">
                                    <x-lucide-user class="w-4 h-4" />
                                    My Profile
                                </a>
                            </div>

                            <div class="border-t border-[var(--border-subtle)] pt-1 mt-0.5">
                                <button @click="logoutModalOpen = true; profileOpen = false" type="button"
                                    class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm font-semibold text-[var(--accent-hover)] hover:bg-[var(--accent-soft)] transition-colors cursor-pointer">
                                    <x-lucide-log-out class="w-4 h-4" />
                                    Log Out
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- TOAST NOTIFICATIONS --}}
            @php
            $toastType = session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : (session('info') ? 'info' : null)));
            $toastMessage = $toastType ? session($toastType) : null;
            @endphp
            @if($toastMessage)
            <div x-data="{ show: false }"
                x-init="show = true; setTimeout(() => show = false, 5000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-8 scale-95"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 translate-x-8 scale-95"
                class="fixed bottom-4 left-4 right-4 sm:bottom-6 sm:left-auto sm:right-6 sm:w-full sm:max-w-sm z-[80] pointer-events-auto"
                style="display:none;" role="alert" aria-live="assertive">

                <div class="card relative overflow-hidden flex items-start gap-3.5 p-4 shadow-2xl shadow-black/60 cursor-default
                    @if($toastType === 'success') border-emerald-500/30
                    @elseif($toastType === 'error') border-[var(--accent-border)]
                    @elseif($toastType === 'warning') border-amber-500/30
                    @else border-blue-500/30
                    @endif">

                    <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center
                        @if($toastType === 'success') bg-emerald-500/10
                        @elseif($toastType === 'error') bg-[var(--accent-soft)]
                        @elseif($toastType === 'warning') bg-amber-500/10
                        @else bg-blue-500/10
                        @endif">
                        @if($toastType === 'success')
                        <x-lucide-check-circle class="w-5 h-5 text-emerald-400" />
                        @elseif($toastType === 'error')
                        <x-lucide-alert-circle class="w-5 h-5 text-[var(--accent-hover)]" />
                        @elseif($toastType === 'warning')
                        <x-lucide-alert-triangle class="w-5 h-5 text-amber-400" />
                        @else
                        <x-lucide-info class="w-5 h-5 text-[var(--accent-alt)]" />
                        @endif
                    </div>

                    <div class="flex-1 min-w-0 pt-0.5">
                        <p class="text-sm font-bold text-white tracking-tight">
                            @if($toastType === 'success') Success
                            @elseif($toastType === 'error') Error
                            @elseif($toastType === 'warning') Warning
                            @else Notice
                            @endif
                        </p>
                        <p class="text-xs text-[var(--text-secondary)] mt-0.5 leading-relaxed">{{ $toastMessage }}</p>
                    </div>

                    <button @click="show = false" type="button"
                        class="flex-shrink-0 p-1.5 -mr-1 -mt-0.5 rounded-lg text-[var(--text-muted)] hover:text-white hover:bg-white/5 transition-colors cursor-pointer"
                        aria-label="Dismiss notification">
                        <x-lucide-x class="w-4 h-4" />
                    </button>

                    <div class="absolute bottom-0 left-0 h-[3px]
                        @if($toastType === 'success') bg-emerald-500
                        @elseif($toastType === 'error') bg-[var(--accent-hover)]
                        @elseif($toastType === 'warning') bg-amber-500
                        @else bg-[var(--accent-alt)]
                        @endif"
                        style="animation: toast-shrink 5s linear forwards;"></div>
                </div>
            </div>
            @endif

            {{-- Content --}}
            <main class="flex-1 flex flex-col overflow-x-hidden overflow-y-auto z-10 relative scroll-area cursor-default min-w-0">

                <div class="page-enter flex-1 p-3 sm:p-5 lg:p-8 max-w-[100rem] mx-auto w-full min-w-0">
                    @yield('content')
                </div>

                <footer class="px-4 sm:px-8 pb-6 pt-2">
                    <div class="max-w-[100rem] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-[var(--text-muted)] border-t border-[var(--border-subtle)] pt-4 cursor-default">
                        <p>© {{ date('Y') }} {{ config('app.name') }} — All rights reserved.</p>
                        <p class="font-mono">v2.1</p>
                    </div>
                </footer>
            </main>

        </div>
    </div>

    <script>
        function toggleTheme() {
            var html = document.documentElement;
            var isLight = html.classList.toggle('light');
            localStorage.setItem('desmos_ai_theme', isLight ? 'light' : 'dark');
            var meta = document.getElementById('theme-color-meta');
            if (meta) meta.content = isLight ? '#f0f2f7' : '#0b0d12';
        }
    </script>

    @stack('scripts')
</body>

</html>