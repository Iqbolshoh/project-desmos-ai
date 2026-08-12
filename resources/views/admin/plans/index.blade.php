@extends('layouts.dashboard')

@section('title', 'Subscription Plans')
@section('header_title', 'Subscription Plans')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <x-lucide-crown class="w-7 h-7 text-[var(--accent)]" />
                Subscription Plans
            </h1>
            <p class="text-[var(--text-secondary)] mt-1">Manage pricing, AI limits and features. Changes take effect immediately.</p>
        </div>
        @can('plans.create')
        <a href="{{ route('admin.plans.create') }}" class="btn-primary flex items-center gap-2">
            <x-lucide-plus class="w-4 h-4" /> New Plan
        </a>
        @endcan
    </div>

    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl flex items-center gap-3">
        <x-lucide-circle-alert class="w-5 h-5 flex-shrink-0" />
        {{ session('error') }}
    </div>
    @endif

    <div class="card bg-[var(--bg-overlay)] border-[var(--border-strong)] overflow-hidden rounded-2xl shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[var(--text-secondary)]">
                <thead class="bg-black/40 text-[var(--text-muted)] font-mono text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Plan</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">AI Access</th>
                        <th class="px-6 py-4">Subscribers</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-subtle)]">
                    @forelse($plans as $plan)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-white flex items-center gap-2">
                                {{ $plan->name }}
                                @if($plan->is_featured)
                                <span class="badge badge-accent text-[0.625rem] font-mono uppercase">Featured</span>
                                @endif
                            </div>
                            <div class="text-xs text-[var(--text-muted)] font-mono mt-0.5">{{ $plan->slug }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-white">
                            {{ $plan->formattedPrice() }}
                            <span class="text-xs text-[var(--text-muted)]">/ {{ $plan->period }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if(!$plan->ai_enabled)
                                <span class="badge bg-red-500/10 text-red-400 text-[0.65rem] font-mono uppercase">Disabled</span>
                            @elseif($plan->ai_daily_limit === null)
                                <span class="badge bg-emerald-500/10 text-emerald-400 text-[0.65rem] font-mono uppercase">Unlimited</span>
                            @else
                                <span class="font-mono">{{ $plan->ai_daily_limit }} / day</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono">{{ $plan->users_count }}</td>
                        <td class="px-6 py-4">
                            @if($plan->is_active)
                                <span class="badge bg-emerald-500/10 text-emerald-400 text-[0.65rem] font-mono uppercase">Active</span>
                            @else
                                <span class="badge bg-gray-500/10 text-gray-400 text-[0.65rem] font-mono uppercase">Hidden</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                @can('plans.edit')
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors p-2 rounded-lg hover:bg-[var(--accent-soft)]" title="Edit Plan">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                                @endcan
                                @can('plans.delete')
                                @if($plan->slug !== \App\Models\Plan::DEFAULT_SLUG)
                                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan? Its subscribers will be moved to the free plan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors p-2 rounded-lg hover:bg-red-500/10" title="Delete Plan">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-[var(--text-muted)]">
                            No plans yet. Run <code class="font-mono">php artisan db:seed --class=PlanSeeder</code> or create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
