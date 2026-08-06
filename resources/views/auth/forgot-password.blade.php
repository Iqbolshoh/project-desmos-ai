@extends('layouts.app')

@section('title', 'Reset Password · Desmos AI v2.1')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-hidden bg-[var(--bg-base)]">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute top-[-10%] left-[-5%] w-[28rem] h-[28rem] rounded-full blur-[120px]" style="background: rgba(59, 130, 246, 0.08);"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[32rem] h-[32rem] rounded-full blur-[140px]" style="background: rgba(129, 140, 248, 0.05);"></div>
    </div>

    <div class="card relative z-10 w-full max-w-md p-8 sm:p-10 space-y-7 shadow-2xl shadow-black/50 page-enter border border-[var(--border-strong)] rounded-3xl">

        <div class="text-center flex flex-col items-center">
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/5 border border-[var(--border-strong)] shadow-sm">
                <x-lucide-key-round class="w-6 h-6 text-[var(--accent-alt)]" />
            </div>

            <h2 class="mt-5 text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                Forgot Password?
            </h2>
            <p class="mt-2 text-sm text-[var(--text-secondary)] text-center">
                Enter your email address and we'll send you a password reset link.
            </p>
        </div>

        @if (session('success'))
        <div class="rounded-[var(--radius-md)] border border-[rgba(52,211,153,0.25)] bg-[rgba(52,211,153,0.1)] px-4 py-3 text-sm text-[var(--success)]">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
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

            <button type="submit" class="btn-primary w-full py-3 text-base mt-2">
                <x-lucide-mail class="w-5 h-5" />
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-sm text-[var(--text-secondary)]">
            <a href="{{ route('login') }}" class="font-semibold text-[var(--accent-alt)] hover:underline">← Return to Sign In</a>
        </p>
    </div>
</div>
@endsection
