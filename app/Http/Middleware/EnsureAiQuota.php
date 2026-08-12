<?php

namespace App\Http\Middleware;

use App\Services\Subscription\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks AI requests once the signed-in user has spent the daily allowance
 * granted by their plan, or when their plan has no AI access at all.
 */
class EnsureAiQuota
{
    public function __construct(private readonly SubscriptionService $subscriptions) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user === null) {
            return $next($request);
        }

        $reason = $this->subscriptions->denialReason($user);

        if ($reason === null) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => $reason,
                'upgrade_url' => route('billing.index'),
            ], 402);
        }

        return redirect()->route('billing.index')->with('warning', $reason);
    }
}
