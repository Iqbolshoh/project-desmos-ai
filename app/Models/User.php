<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar_url',
        'plan_id',
        'plan_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'plan_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function questionAttempts(): HasMany
    {
        return $this->hasMany(QuestionAttempt::class);
    }

    public function diagnosticResults(): HasMany
    {
        return $this->hasMany(DiagnosticResult::class);
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function aiTutorSessions(): HasMany
    {
        return $this->hasMany(AiTutorSession::class);
    }

    public function chatThreads(): HasMany
    {
        return $this->hasMany(ChatThread::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * The plan actually in force right now: the subscribed plan while it is
     * still valid, otherwise the default free plan.
     */
    public function activePlan(): ?Plan
    {
        if ($this->plan_id && ! $this->planExpired()) {
            return $this->plan;
        }

        return Plan::default();
    }

    /**
     * A subscription with no expiry date never lapses.
     */
    public function planExpired(): bool
    {
        return $this->plan_expires_at !== null && $this->plan_expires_at->isPast();
    }

    /**
     * Premium means a paid plan that has not lapsed.
     */
    public function isPremium(): bool
    {
        $plan = $this->activePlan();

        return $plan !== null && ! $plan->isFree();
    }

    /**
     * Avatar to render: an uploaded file wins over the Google picture, and
     * null means the caller should fall back to the initials badge.
     */
    public function avatarUrl(): ?string
    {
        $uploaded = $this->studentProfile?->avatar_path;

        if ($uploaded && Storage::disk('public')->exists($uploaded)) {
            return Storage::disk('public')->url($uploaded);
        }

        return $this->avatar_url;
    }
}
