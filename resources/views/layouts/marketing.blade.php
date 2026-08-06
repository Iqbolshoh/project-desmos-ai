<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0d12">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>@yield('title', config('app.name') . ' — SAT Math AI Tutor')</title>

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
            min-height: 100vh;
        }

        .card {
            background: var(--bg-raised);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(12px);
        }

        .btn-primary,
        .btn-secondary,
        .btn-ghost,
        .btn-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem 1.375rem;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all .2s var(--ease-out);
            border: none;
            text-decoration: none;
        }

        .btn-gold {
            color: #1a1206;
            font-weight: 700;
            background: linear-gradient(135deg, var(--gold), var(--gold-alt));
            box-shadow: 0 4px 18px -4px var(--gold-glow), inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, var(--gold-hover), var(--gold-alt));
            transform: translateY(-1px);
            box-shadow: 0 8px 28px -4px var(--gold-glow), inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .btn-secondary {
            color: var(--text-primary);
            background: var(--bg-overlay);
            border: 1px solid var(--border-strong);
        }

        .btn-secondary:hover {
            background: #2d3a52;
            border-color: rgba(148, 163, 184, 0.22);
            transform: translateY(-1px);
        }

        .btn-ghost {
            color: var(--text-secondary);
        }

        .btn-ghost:hover {
            color: var(--text-primary);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.725rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .badge-gold {
            background: var(--gold-soft);
            color: var(--gold-hover);
            border: 1px solid var(--gold-border);
        }

        .gold-text {
            background: linear-gradient(135deg, var(--gold), var(--gold-alt));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes page-enter {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-enter {
            animation: page-enter .55s var(--ease-out) both;
        }

        :focus-visible {
            outline: 2px solid var(--gold);
            outline-offset: 2px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased selection:bg-[var(--gold)] selection:text-black">

    <header class="sticky top-0 z-40 border-b border-[var(--border-subtle)] backdrop-blur-xl bg-[var(--bg-base)]/80">
        <nav class="max-w-7xl mx-auto px-4 sm:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 flex items-center justify-center rounded-xl overflow-hidden border border-[var(--gold-border)] bg-white/5 p-1 transition-transform duration-300 group-hover:scale-105">
                    <img src="{{ asset('/images/logo.png') }}" alt="Desmos AI Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-extrabold text-lg tracking-tight gold-text">Desmos AI v2.1</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-[var(--text-secondary)]">
                <a href="#features" class="hover:text-[var(--text-primary)] transition-colors">Features</a>
                <a href="#stats" class="hover:text-[var(--text-primary)] transition-colors">Performance</a>
                <a href="#pricing" class="hover:text-[var(--text-primary)] transition-colors">Plans</a>
                <a href="#faq" class="hover:text-[var(--text-primary)] transition-colors">FAQ</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="btn-ghost hidden sm:inline-flex">Sign In</a>
                <a href="{{ route('register') }}" class="btn-gold">
                    <x-lucide-sparkles class="w-4 h-4" />
                    Get Started Free
                </a>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-[var(--border-subtle)] mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[var(--text-muted)]">
            <p>© {{ date('Y') }} Desmos AI — All rights reserved.</p>
            <p class="font-mono">AI platform for Digital SAT Math 800/800 mastery</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
