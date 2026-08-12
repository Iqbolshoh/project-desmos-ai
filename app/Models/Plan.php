<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    /** Slug of the plan every user falls back to when they have none. */
    public const DEFAULT_SLUG = 'free';

    protected $fillable = [
        'slug',
        'name',
        'tagline',
        'price',
        'currency',
        'period',
        'ai_enabled',
        'ai_daily_limit',
        'features',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'ai_enabled' => 'boolean',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * The plan assigned to anyone without a subscription, and the plan an
     * expired subscription falls back to.
     */
    public static function default(): ?self
    {
        return static::where('slug', self::DEFAULT_SLUG)->first();
    }

    /**
     * A plan is free when it costs nothing — used to decide whether a user
     * counts as "premium" rather than hard-coding slugs across the app.
     */
    public function isFree(): bool
    {
        return (float) $this->price <= 0;
    }

    /**
     * Price formatted for display, e.g. "$0" or "$19.50".
     */
    public function formattedPrice(): string
    {
        $symbol = match ($this->currency) {
            'USD' => '$',
            'EUR' => '€',
            'UZS' => '',
            default => $this->currency.' ',
        };

        $amount = (float) $this->price;
        $decimals = fmod($amount, 1.0) === 0.0 ? 0 : 2;
        $formatted = number_format($amount, $decimals, '.', ' ');

        return $this->currency === 'UZS'
            ? $formatted.' UZS'
            : $symbol.$formatted;
    }

    /**
     * Human-readable description of the daily AI allowance.
     */
    public function aiLimitLabel(): string
    {
        if (! $this->ai_enabled) {
            return 'No AI access';
        }

        return $this->ai_daily_limit === null
            ? 'Unlimited AI requests'
            : $this->ai_daily_limit.' AI requests per day';
    }
}
