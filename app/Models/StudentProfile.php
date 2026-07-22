<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar_path',
        'sat_goal_score',
        'sat_current_score',
        'xp',
        'level',
        'streak_current',
        'streak_longest',
        'last_activity_date',
        'daily_goal_minutes',
        'onboarded_at',
    ];

    protected function casts(): array
    {
        return [
            'last_activity_date' => 'date',
            'onboarded_at'       => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
