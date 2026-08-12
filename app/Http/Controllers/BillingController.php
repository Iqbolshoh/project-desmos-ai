<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptions) {}

    /**
     * Show the signed-in user their plan, today's AI usage and the upgrades
     * available to them.
     */
    public function index(): View
    {
        $user = Auth::user();

        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        return view('billing.index', [
            'user' => $user,
            'plans' => $plans,
            'currentPlan' => $user->activePlan(),
            'usageToday' => $this->subscriptions->aiUsageToday($user),
            'remaining' => $this->subscriptions->remainingAiRequests($user),
        ]);
    }
}
