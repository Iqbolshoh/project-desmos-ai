@extends('layouts.dashboard')

@section('title', 'My Plan')
@section('header_title', 'My Plan')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    @if(session('warning'))
    <div class="bg-amber-500/10 border border-amber-500/30 text-amber-400 p-4 rounded-xl flex items-center gap-3">
        <x-lucide-triangle-alert class="w-5 h-5 flex-shrink-0" />
        {{ session('warning') }}
    </div>
    @endif

    {{-- Current plan & usage --}}
    <div class="card bg-[var(--bg-overlay)] border-[var(--border-strong)] rounded-2xl shadow-xl p-8">
        <div class="flex items-start justify-between flex-wrap gap-6">
            <div>
                <p class="text-xs font-mono text-[var(--text-muted)] uppercase tracking-wider">Current Plan</p>
                <h1 class="text-3xl font-extrabold text-white mt-1 flex items-center gap-3">
                    {{ $currentPlan?->name ?? 'Free' }}
                    @if($user->isPremium())
                    <span class="badge badge-accent font-mono text-xs uppercase">Premium</span>
                    @endif
                </h1>
                <p class="text-[var(--text-secondary)] mt-1">{{ $currentPlan?->aiLimitLabel() }}</p>
                @if($user->plan_expires_at && $user->isPremium())
                <p class="text-xs text-[var(--text-muted)] mt-2 font-mono">
                    Valid until {{ $user->plan_expires_at->format('M j, Y') }}
                </p>
                @endif
            </div>

            <div class="text-right">
                <p class="text-xs font-mono text-[var(--text-muted)] uppercase tracking-wider">AI usage today</p>
                <p class="text-3xl font-extrabold font-mono text-white mt-1">
                    {{ $usageToday }}<span class="text-base text-[var(--text-muted)]">
                        / {{ $remaining === null ? '∞' : ($currentPlan?->ai_daily_limit ?? 0) }}</span>
                </p>
                @if($remaining !== null)
                <p class="text-xs text-[var(--text-muted)] mt-1">{{ $remaining }} requests left today</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Available plans --}}
    <div>
        <h2 class="text-xl font-bold text-white mb-4">Available Plans</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            @foreach($plans as $plan)
            @php $isCurrent = $currentPlan && $currentPlan->id === $plan->id; @endphp
            <div class="card p-8 rounded-2xl {{ $plan->is_featured ? 'border-2 border-[var(--accent)] shadow-2xl' : 'shadow-xl' }} relative">
                @if($plan->is_featured)
                <span class="badge badge-accent absolute -top-3 left-1/2 -translate-x-1/2 font-mono">Most Popular</span>
                @endif

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[var(--text-primary)]">{{ $plan->name }}</h3>
                    @if($isCurrent)
                    <span class="badge bg-emerald-500/10 text-emerald-400 font-mono text-[0.65rem] uppercase">Your plan</span>
                    @endif
                </div>
                @if($plan->tagline)
                <p class="text-sm text-[var(--text-secondary)] mt-1">{{ $plan->tagline }}</p>
                @endif

                <p class="mt-4">
                    <span class="text-3xl font-extrabold font-mono">{{ $plan->formattedPrice() }}</span>
                    <span class="text-xs text-[var(--text-muted)] font-mono"> / {{ $plan->period }}</span>
                </p>

                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-2.5 text-sm text-[var(--text-secondary)]">
                        <x-lucide-bot class="w-4 h-4 mt-0.5 shrink-0 text-[var(--accent)]" />
                        {{ $plan->aiLimitLabel() }}
                    </li>
                    @foreach($plan->features ?? [] as $feature)
                    <li class="flex items-start gap-2.5 text-sm text-[var(--text-secondary)]">
                        <x-lucide-check-circle class="w-4 h-4 mt-0.5 shrink-0 text-[var(--accent)]" />
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                @if(!$isCurrent && !$plan->isFree())
                <div class="mt-8 p-3 rounded-xl bg-black/20 border border-[var(--border-subtle)] text-xs text-[var(--text-muted)] flex items-start gap-2">
                    <x-lucide-info class="w-4 h-4 shrink-0 mt-0.5 text-[var(--accent)]" />
                    Online payment is coming soon. To upgrade now, contact the administrator.
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
