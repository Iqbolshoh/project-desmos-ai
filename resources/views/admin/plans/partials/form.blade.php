@php
    /** @var \App\Models\Plan|null $plan */
    $plan = $plan ?? null;
    $isDefaultPlan = $plan && $plan->slug === \App\Models\Plan::DEFAULT_SLUG;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label for="name" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Plan Name</label>
        <input id="name" name="name" type="text" required maxlength="60"
            value="{{ old('name', $plan?->name) }}"
            class="input @error('name') border-red-500/60 @enderror" placeholder="Premium">
        @error('name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="slug" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Slug</label>
        <input id="slug" name="slug" type="text" maxlength="60"
            value="{{ old('slug', $plan?->slug) }}"
            {{ $isDefaultPlan ? 'readonly' : '' }}
            class="input font-mono {{ $isDefaultPlan ? 'opacity-60 cursor-not-allowed' : '' }} @error('slug') border-red-500/60 @enderror"
            placeholder="premium (auto from name if empty)">
        @if($isDefaultPlan)<p class="text-xs text-[var(--text-muted)] mt-1">The default free plan's slug cannot change.</p>@endif
        @error('slug')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label for="tagline" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Tagline</label>
    <input id="tagline" name="tagline" type="text" maxlength="150"
        value="{{ old('tagline', $plan?->tagline) }}"
        class="input" placeholder="Unlimited AI tutoring for serious prep">
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div>
        <label for="price" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Price</label>
        <input id="price" name="price" type="number" step="0.01" min="0" required
            value="{{ old('price', $plan?->price ?? 0) }}"
            class="input font-mono @error('price') border-red-500/60 @enderror">
        @error('price')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="currency" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Currency</label>
        <select id="currency" name="currency" class="input">
            @foreach(['USD', 'EUR', 'UZS'] as $currency)
            <option value="{{ $currency }}" @selected(old('currency', $plan?->currency ?? 'USD') === $currency)>{{ $currency }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="period" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Billing Period</label>
        <select id="period" name="period" class="input">
            @foreach(['month' => 'Monthly', 'year' => 'Yearly', 'forever' => 'Forever (free)'] as $value => $label)
            <option value="{{ $value }}" @selected(old('period', $plan?->period ?? 'month') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

@php
    $aiEnabledOld = (bool) old('ai_enabled', $plan?->ai_enabled ?? true);
    $aiUnlimitedOld = (bool) old('ai_unlimited', $plan ? $plan->ai_enabled && $plan->ai_daily_limit === null : false);
@endphp

<div class="card bg-black/20 border border-[var(--border-subtle)] rounded-xl p-5 space-y-4"
     x-data="{ aiEnabled: {{ $aiEnabledOld ? 'true' : 'false' }}, aiUnlimited: {{ $aiUnlimitedOld ? 'true' : 'false' }} }">
    <p class="text-sm font-bold text-white flex items-center gap-2">
        <x-lucide-bot class="w-4 h-4 text-[var(--accent)]" /> AI Access
    </p>

    <label class="flex items-center gap-2.5 cursor-pointer">
        <input type="hidden" name="ai_enabled" value="0">
        <input type="checkbox" name="ai_enabled" value="1" x-model="aiEnabled"
            class="w-4 h-4 rounded border-[var(--border-strong)] bg-[var(--bg-surface)]">
        <span class="text-sm text-[var(--text-secondary)]">AI tutor &amp; AI chat enabled on this plan</span>
    </label>

    <div x-show="aiEnabled" class="space-y-4">
        <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="hidden" name="ai_unlimited" value="0">
            <input type="checkbox" name="ai_unlimited" value="1" x-model="aiUnlimited"
                class="w-4 h-4 rounded border-[var(--border-strong)] bg-[var(--bg-surface)]">
            <span class="text-sm text-[var(--text-secondary)]">Unlimited requests</span>
        </label>

        <div x-show="!aiUnlimited">
            <label for="ai_daily_limit" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Daily Request Limit</label>
            <input id="ai_daily_limit" name="ai_daily_limit" type="number" min="0" max="100000"
                value="{{ old('ai_daily_limit', $plan?->ai_daily_limit ?? 3) }}"
                class="input font-mono w-40 @error('ai_daily_limit') border-red-500/60 @enderror">
            <p class="text-xs text-[var(--text-muted)] mt-1">Solver and chat requests both count towards this limit.</p>
            @error('ai_daily_limit')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div>
    <label for="features" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Features (one per line)</label>
    <textarea id="features" name="features" rows="5"
        class="input font-mono text-sm resize-y"
        placeholder="Unlimited AI tutor solutions&#10;24/7 AI Chat Tutor">{{ old('features', $plan ? implode("\n", $plan->features ?? []) : '') }}</textarea>
    <p class="text-xs text-[var(--text-muted)] mt-1">Shown as the bullet list on the pricing and billing pages.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
    <label class="flex items-center gap-2.5 cursor-pointer">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $plan?->is_active ?? true))
            class="w-4 h-4 rounded border-[var(--border-strong)] bg-[var(--bg-surface)]">
        <span class="text-sm text-[var(--text-secondary)]">Active (visible to students)</span>
    </label>

    <label class="flex items-center gap-2.5 cursor-pointer">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" @checked((bool) old('is_featured', $plan?->is_featured ?? false))
            class="w-4 h-4 rounded border-[var(--border-strong)] bg-[var(--bg-surface)]">
        <span class="text-sm text-[var(--text-secondary)]">Featured (highlighted card)</span>
    </label>

    <div>
        <label for="sort_order" class="block text-xs font-semibold text-[var(--text-muted)] mb-1.5 uppercase tracking-wide">Sort Order</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="1000"
            value="{{ old('sort_order', $plan?->sort_order ?? 0) }}"
            class="input font-mono w-28">
    </div>
</div>
