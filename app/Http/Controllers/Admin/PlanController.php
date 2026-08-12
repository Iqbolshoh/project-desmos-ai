<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlanController extends Controller
{
    /**
     * List every subscription plan with its subscriber count.
     */
    public function index(): View
    {
        $this->authorizeAction('plans.view');

        $plans = Plan::withCount('users')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        $this->authorizeAction('plans.create');

        return view('admin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAction('plans.create');

        $data = $this->validated($request);

        $plan = Plan::create($data);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan \"{$plan->name}\" created successfully.");
    }

    public function edit(Plan $plan): View
    {
        $this->authorizeAction('plans.edit');

        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $this->authorizeAction('plans.edit');

        $data = $this->validated($request, $plan);

        $plan->update($data);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan \"{$plan->name}\" updated successfully.");
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $this->authorizeAction('plans.delete');

        // Every user falls back to the free plan, so removing it would leave
        // the app with no baseline tier at all.
        if ($plan->slug === Plan::DEFAULT_SLUG) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'The default free plan cannot be deleted.');
        }

        $name = $plan->name;

        // Subscribers fall back to the free plan rather than losing their row.
        $plan->users()->update([
            'plan_id' => Plan::default()?->id,
            'plan_expires_at' => null,
        ]);

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan \"{$name}\" deleted. Its subscribers moved to the free plan.");
    }

    /**
     * Validate the plan form and normalise it into model attributes.
     */
    private function validated(Request $request, ?Plan $plan = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'slug' => ['nullable', 'string', 'max:60', 'alpha_dash', Rule::unique('plans', 'slug')->ignore($plan?->id)],
            'tagline' => ['nullable', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'currency' => ['required', 'string', Rule::in(['USD', 'EUR', 'UZS'])],
            'period' => ['required', 'string', Rule::in(['month', 'year', 'forever'])],
            'ai_enabled' => ['nullable', 'boolean'],
            'ai_unlimited' => ['nullable', 'boolean'],
            'ai_daily_limit' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'features' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ], [
            'price.numeric' => 'The price must be a number.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes and underscores.',
        ]);

        $aiEnabled = $request->boolean('ai_enabled');

        return [
            'name' => $data['name'],
            // The free plan's slug is what Plan::default() looks up, so it is
            // fixed once created; everything else may be renamed freely.
            'slug' => $plan?->slug === Plan::DEFAULT_SLUG
                ? Plan::DEFAULT_SLUG
                : Str::slug($data['slug'] ?: $data['name']),
            'tagline' => $data['tagline'] ?? null,
            'price' => $data['price'],
            'currency' => $data['currency'],
            'period' => $data['period'],
            'ai_enabled' => $aiEnabled,
            'ai_daily_limit' => $aiEnabled && ! $request->boolean('ai_unlimited')
                ? (int) ($data['ai_daily_limit'] ?? 0)
                : null,
            'features' => $this->parseFeatures($data['features'] ?? null),
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }

    /**
     * The form takes one feature per line; store them as a clean array.
     */
    private function parseFeatures(?string $raw): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $raw))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function authorizeAction(string $permission): void
    {
        abort_unless(Auth::user()?->hasPermissionTo($permission), 403);
    }
}
