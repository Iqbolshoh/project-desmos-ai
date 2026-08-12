@extends('layouts.app')

@section('has_nav', 'yes')
@section('title', 'Sign In · ' . config('app.program_name'))

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-hidden bg-[var(--bg-base)]">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute top-[-10%] left-[-5%] w-[28rem] h-[28rem] rounded-full blur-[120px]" style="background: rgba(59, 130, 246, 0.08);"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[32rem] h-[32rem] rounded-full blur-[140px]" style="background: rgba(129, 140, 248, 0.05);"></div>
    </div>

    <div class="card relative z-10 w-full max-w-md p-8 sm:p-10 space-y-7 shadow-2xl shadow-black/50 page-enter border border-[var(--border-strong)] rounded-3xl">

        <div class="text-center flex flex-col items-center">
            <a href="{{ route('home') }}" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/5 border border-[var(--border-strong)] shadow-sm transition-transform duration-300 hover:scale-105 overflow-hidden">
                <img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.program_name') }}" class="w-full h-full object-contain p-1.5">
            </a>

            <h2 class="mt-5 text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                Welcome Back
            </h2>
            <p class="mt-2 text-sm text-[var(--text-secondary)]">
                Sign in to your {{ config('app.program_name') }} account
            </p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-[var(--text-primary)] mb-1.5">
                    Email Address
                </label>
                <input id="email" name="email" type="email" required
                    value="{{ old('email') }}"
                    autocomplete="email"
                    class="input @error('email') !border-[var(--accent)] !shadow-[0_0_0_3px_var(--accent-soft)] @enderror"
                    placeholder="student@example.com">

                @error('email')
                <p class="mt-2 text-xs font-medium text-[var(--accent-alt)] flex items-center gap-1.5">
                    <x-lucide-circle-alert class="w-3.5 h-3.5 shrink-0" />
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div x-data="{ showPassword: false }">
                <label for="password" class="block text-sm font-semibold text-[var(--text-primary)] mb-1.5">
                    Password
                </label>
                <div class="relative">
                    <input id="password" name="password"
                        :type="showPassword ? 'text' : 'password'"
                        required
                        autocomplete="current-password"
                        class="input pr-12"
                        placeholder="••••••••">

                    <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[var(--text-muted)] hover:text-[var(--accent-alt)] focus:outline-none transition-colors cursor-pointer">
                        <x-lucide-eye :class="showPassword ? 'hidden' : 'block'" class="w-5 h-5" />
                        <x-lucide-eye-off :class="showPassword ? 'block' : 'hidden'" class="w-5 h-5 hidden" />
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" name="remember" checked
                        class="w-4 h-4 rounded border-[var(--border-strong)] bg-[var(--bg-surface)] text-[var(--accent)] focus:ring-[var(--accent)] cursor-pointer transition-colors">
                    <span class="text-[var(--text-secondary)] group-hover:text-white transition-colors">
                        Remember me
                    </span>
                </label>

                <a href="{{ route('password.request') }}" class="font-semibold text-[var(--accent-alt)] hover:underline">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn-primary w-full py-3 text-base mt-2">
                <x-lucide-log-in class="w-5 h-5" />
                Sign In
            </button>
        </form>

        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-[var(--border-strong)]"></div>
            <span class="text-xs font-medium text-[var(--text-muted)] font-mono">or</span>
            <div class="h-px flex-1 bg-[var(--border-strong)]"></div>
        </div>

        <a href="{{ route('auth.google.redirect') }}"
            class="btn-secondary w-full py-3 text-base">
            <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z" />
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.09C3.26 21.3 7.31 24 12 24z" />
                <path fill="#FBBC05" d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.29A11.96 11.96 0 0 0 0 12c0 1.93.46 3.76 1.29 5.38l3.98-3.09z" />
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.98 3.09C6.22 6.86 8.87 4.75 12 4.75z" />
            </svg>
            Sign in with Google
        </a>

        <p class="text-center text-sm text-[var(--text-secondary)]">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-[var(--accent-alt)] hover:underline">Sign Up</a>
        </p>
    </div>
</div>
@endsection
