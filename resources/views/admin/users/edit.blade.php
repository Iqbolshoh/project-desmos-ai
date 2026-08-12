@extends('layouts.dashboard')

@section('title', 'Edit User')
@section('breadcrumb', 'Users')
@section('header_title', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto">

    <a href="{{ route('users.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--text-muted)] hover:text-white transition-colors mb-6">
        <x-lucide-arrow-left class="w-4 h-4" />
        Back to users
    </a>

    <div class="card p-6 sm:p-8">
        <div class="mb-8 pb-6 border-b border-[var(--border-subtle)] flex items-center gap-4">
            <div class="flex-1">
                <h2 class="text-xl font-bold text-white tracking-tight">Edit user</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Editing: <span class="font-semibold text-[var(--text-secondary)]">{{ $user->name }}</span>
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                ID {{ $user->id }}
            </span>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        To'liq ism <span class="text-[var(--accent)]">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="input @error('name') border-[var(--accent)] @enderror"
                           required autocomplete="name">
                    @error('name')
                        <p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1">
                            <x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        Email manzil <span class="text-[var(--accent)]">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="input @error('email') border-[var(--accent)] @enderror"
                           required autocomplete="email">
                    @error('email')
                        <p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1">
                            <x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-[var(--border-subtle)]">
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        New password <span class="text-[var(--text-muted)] font-normal">(optional)</span>
                    </label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                               class="input pr-11 @error('password') border-[var(--accent)] @enderror"
                               placeholder="Leave blank to keep unchanged" autocomplete="new-password">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-md text-[var(--text-muted)] hover:text-[var(--text-secondary)] transition-colors">
                            <x-lucide-eye-off x-show="show" class="w-4 h-4" />
                            <x-lucide-eye x-show="!show" class="w-4 h-4" />
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1">
                            <x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}
                        </p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        Confirm new password
                    </label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                               class="input pr-11" placeholder="Repeat new password" autocomplete="new-password">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-md text-[var(--text-muted)] hover:text-[var(--text-secondary)] transition-colors">
                            <x-lucide-eye-off x-show="show" class="w-4 h-4" />
                            <x-lucide-eye x-show="!show" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <label for="role" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                    Role <span class="text-[var(--accent)]">*</span>
                </label>
                <select name="role" id="role"
                        class="input cursor-pointer @error('role') border-[var(--accent)] @enderror" required>
                    <option value="" disabled>Select a role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}"
                            {{ old('role', $userRole) === $role->name ? 'selected' : '' }}>
                        {{ $role->name === 'superadmin' ? 'Super Admin' : ucfirst(str_replace(['-', '_'], ' ', $role->name)) }}
                    </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1">
                        <x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="plan_id" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        Subscription plan
                    </label>
                    <select name="plan_id" id="plan_id"
                            class="input cursor-pointer @error('plan_id') border-[var(--accent)] @enderror">
                        <option value="">— Default (Free) —</option>
                        @foreach($plans as $planOption)
                        <option value="{{ $planOption->id }}"
                                {{ (string) old('plan_id', $user->plan_id) === (string) $planOption->id ? 'selected' : '' }}>
                            {{ $planOption->name }} ({{ $planOption->formattedPrice() }}/{{ $planOption->period }})
                        </option>
                        @endforeach
                    </select>
                    @error('plan_id')
                        <p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1">
                            <x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="plan_expires_at" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
                        Plan valid until <span class="text-[var(--text-muted)] font-normal">(blank = no expiry)</span>
                    </label>
                    <input type="date" id="plan_expires_at" name="plan_expires_at"
                           value="{{ old('plan_expires_at', $user->plan_expires_at?->format('Y-m-d')) }}"
                           class="input @error('plan_expires_at') border-[var(--accent)] @enderror">
                    @error('plan_expires_at')
                        <p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1">
                            <x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-[var(--border-subtle)]">
                <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <x-lucide-save class="w-4 h-4" />
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
