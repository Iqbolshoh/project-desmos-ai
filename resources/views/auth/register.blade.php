@extends('layouts.app')

@section('title', 'Sign Up · Desmos AI v2.1')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-hidden bg-[var(--bg-base)]">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute top-[-10%] left-[-5%] w-[28rem] h-[28rem] rounded-full blur-[120px]" style="background: rgba(59, 130, 246, 0.08);"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[32rem] h-[32rem] rounded-full blur-[140px]" style="background: rgba(129, 140, 248, 0.05);"></div>
    </div>

    <div class="card relative z-10 w-full max-w-md p-8 sm:p-10 space-y-7 shadow-2xl shadow-black/50 page-enter border border-[var(--border-strong)] rounded-3xl">

        <div class="text-center flex flex-col items-center">
            <a href="{{ route('home') }}" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/5 border border-[var(--border-strong)] shadow-sm transition-transform duration-300 hover:scale-105 overflow-hidden">
                <img src="{{ asset('/images/logo.png') }}" alt="Desmos AI Logo" class="w-full h-full object-contain p-1.5">
            </a>

            <h2 class="mt-5 text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                Create Account
            </h2>
            <p class="mt-2 text-sm text-[var(--text-secondary)]">
                Start your Digital SAT Math prep with Desmos AI
            </p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-[var(--text-primary)] mb-1.5">
                    Full Name
                </label>
                <input id="name" name="name" type="text" required
                    value="{{ old('name') }}"
                    autocomplete="name"
                    class="input @error('name') !border-[var(--accent)] !shadow-[0_0_0_3px_var(--accent-soft)] @enderror"
                    placeholder="John Doe">

                @error('name')
                <p class="mt-2 text-xs font-medium text-[var(--accent-alt)] flex items-center gap-1.5">
                    <x-lucide-circle-alert class="w-3.5 h-3.5 shrink-0" />
                    {{ $message }}
                </p>
                @enderror
            </div>

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
                        autocomplete="new-password"
                        class="input pr-12 @error('password') !border-[var(--accent)] !shadow-[0_0_0_3px_var(--accent-soft)] @enderror"
                        placeholder="Minimum 8 characters">

                    <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[var(--text-muted)] hover:text-[var(--accent-alt)] focus:outline-none transition-colors cursor-pointer">
                        <x-lucide-eye :class="showPassword ? 'hidden' : 'block'" class="w-5 h-5" />
                        <x-lucide-eye-off :class="showPassword ? 'block' : 'hidden'" class="w-5 h-5 hidden" />
                    </button>
                </div>

                @error('password')
                <p class="mt-2 text-xs font-medium text-[var(--accent-alt)] flex items-center gap-1.5">
                    <x-lucide-circle-alert class="w-3.5 h-3.5 shrink-0" />
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-[var(--text-primary)] mb-1.5">
                    Confirm Password
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    autocomplete="new-password"
                    class="input"
                    placeholder="••••••••">
            </div>

            <button type="submit" class="btn-primary w-full py-3 text-base mt-2">
                <x-lucide-user-plus class="w-5 h-5" />
                Create Account
            </button>
        </form>

        <p class="text-center text-sm text-[var(--text-secondary)]">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-[var(--accent-alt)] hover:underline">Sign In</a>
        </p>
    </div>
</div>
@endsection
