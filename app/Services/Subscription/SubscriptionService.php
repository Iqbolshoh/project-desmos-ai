<?php

namespace App\Services\Subscription;

use App\Models\ChatMessage;
use App\Models\Plan;
use App\Models\User;

class SubscriptionService
{
    /**
     * How many AI requests the user has spent since midnight.
     *
     * Both the solver and the chat tutor bill against the same allowance, so
     * a user cannot dodge the limit by switching between the two.
     */
    public function aiUsageToday(User $user): int
    {
        $solves = $user->aiTutorSessions()
            ->whereDate('created_at', today())
            ->count();

        $chats = ChatMessage::where('role', 'user')
            ->whereDate('created_at', today())
            ->whereHas('thread', fn ($query) => $query->where('user_id', $user->id))
            ->count();

        return $solves + $chats;
    }

    /**
     * Requests left today, or null when the plan is unlimited.
     */
    public function remainingAiRequests(User $user): ?int
    {
        $plan = $user->activePlan();

        if ($plan === null || ! $plan->ai_enabled) {
            return 0;
        }

        if ($plan->ai_daily_limit === null) {
            return null;
        }

        return max(0, $plan->ai_daily_limit - $this->aiUsageToday($user));
    }

    public function canUseAi(User $user): bool
    {
        $remaining = $this->remainingAiRequests($user);

        return $remaining === null || $remaining > 0;
    }

    /**
     * Message shown when the request is refused, or null when it is allowed.
     */
    public function denialReason(User $user): ?string
    {
        $plan = $user->activePlan();

        if ($plan === null || ! $plan->ai_enabled) {
            return 'AI features are not included in your current plan. Upgrade to unlock the AI tutor.';
        }

        if ($this->canUseAi($user)) {
            return null;
        }

        $upgrade = Plan::where('is_active', true)
            ->where('price', '>', 0)
            ->orderBy('price')
            ->first();

        $suggestion = $upgrade
            ? " Upgrade to {$upgrade->name} for unlimited access."
            : '';

        return "You have used all {$plan->ai_daily_limit} daily AI requests on the {$plan->name} plan.".$suggestion;
    }
}
