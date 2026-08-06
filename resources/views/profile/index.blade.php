@extends('layouts.dashboard')

@php
use Illuminate\Support\Facades\Storage;

$colors = ['bg-blue-500/20 text-blue-400','bg-indigo-500/20 text-indigo-400','bg-violet-500/20 text-violet-400','bg-sky-500/20 text-sky-400'];
$avatarColor = $colors[$user->id % count($colors)];
$roles = $user->getRoleNames();
@endphp

@section('title', 'My Profile')
@section('header_title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Avatar Card --}}
    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-raised)] rounded-2xl shadow-xl">
        <div class="flex items-center gap-5">
            @if ($studentProfile?->avatar_path)
                <img src="{{ Storage::url($studentProfile->avatar_path) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover flex-shrink-0 border border-[var(--border-strong)]">
            @else
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-extrabold text-2xl flex-shrink-0 {{ $avatarColor }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h2 class="text-xl font-extrabold text-white">{{ $user->name }}</h2>
                <p class="text-sm text-[var(--text-muted)] mt-0.5 font-mono">{{ $user->email }}</p>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    @foreach($roles as $role)
                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold font-mono" style="background:var(--accent-soft);color:var(--accent-hover);border:1px solid var(--accent-border)">
                        {{ $role }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    @if ($studentProfile)
    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-raised)] rounded-2xl shadow-xl">
        <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-5 font-mono">Student Performance Metrics</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <div class="text-xs text-[var(--text-muted)] font-mono">XP Total</div>
                <div class="text-xl font-extrabold text-[var(--gold)] font-mono mt-1">{{ number_format($studentProfile->xp, 0, '.', ' ') }}</div>
            </div>
            <div>
                <div class="text-xs text-[var(--text-muted)] font-mono">Level</div>
                <div class="text-xl font-extrabold text-white font-mono mt-1">{{ $studentProfile->level }}</div>
            </div>
            <div>
                <div class="text-xs text-[var(--text-muted)] font-mono">Streak</div>
                <div class="text-xl font-extrabold text-white font-mono mt-1">{{ $studentProfile->streak_current }} days</div>
            </div>
            <div>
                <div class="text-xs text-[var(--text-muted)] font-mono">SAT Score</div>
                <div class="text-xl font-extrabold text-white font-mono mt-1">
                    {{ $studentProfile->sat_current_score ?? '—' }} / {{ $studentProfile->sat_goal_score ?? '—' }}
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Achievements --}}
    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-raised)] rounded-2xl shadow-xl">
        <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-5 font-mono">Earned Achievements</h3>
        @if ($earnedAchievements->isEmpty())
        <p class="text-sm text-[var(--text-muted)]">No achievements unlocked yet. Complete diagnostic assessments and practice questions to earn badges!</p>
        @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($earnedAchievements as $userAchievement)
            <div class="flex flex-col items-center text-center gap-2 p-4 rounded-xl border border-[var(--border-strong)] bg-[var(--bg-overlay)]">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-[var(--accent-soft)] border border-[var(--accent-border)]">
                    <x-dynamic-component :component="'lucide-' . $userAchievement->achievement->icon" class="w-5 h-5 text-[var(--accent-hover)]" />
                </div>
                <div class="text-xs font-bold text-white">{{ $userAchievement->achievement->name }}</div>
                <div class="text-[10px] text-[var(--text-muted)] font-mono">{{ $userAchievement->earned_at->format('M d, Y') }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Account Details --}}
    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-raised)] rounded-2xl shadow-xl">
        <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-5 font-mono">Account Details</h3>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Full Name</label>
                <input name="name" value="{{ old('name', $user->name) }}" class="input @error('name') border-red-500/60 @enderror" required>
                @error('name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Email Address</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="input @error('email') border-red-500/60 @enderror" required>
                @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            @if ($studentProfile)
            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Profile Avatar</label>
                <input name="avatar" type="file" accept="image/*" class="input @error('avatar') border-red-500/60 @enderror">
                @error('avatar')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>
            @endif

            <div class="pt-2">
                <button type="submit" class="btn-primary">
                    <x-lucide-save class="w-4 h-4" /> Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="card p-6 border-[var(--border-strong)] bg-[var(--bg-raised)] rounded-2xl shadow-xl">
        <h3 class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-5 font-mono">Update Password</h3>
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Current Password</label>
                <input name="current_password" type="password" class="input @error('current_password') border-red-500/60 @enderror" placeholder="••••••••">
                @error('current_password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">New Password</label>
                <input name="password" type="password" class="input @error('password') border-red-500/60 @enderror" placeholder="Minimum 6 characters">
                @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Confirm New Password</label>
                <input name="password_confirmation" type="password" class="input" placeholder="••••••••">
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-secondary">
                    <x-lucide-lock class="w-4 h-4" /> Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- System Meta --}}
    <div class="card p-4 rounded-xl">
        <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-[var(--text-muted)] font-mono">
            <span>User ID: #{{ $user->id }}</span>
            <span>Registered: {{ $user->created_at->format('M d, Y') }}</span>
        </div>
    </div>

</div>
@endsection
