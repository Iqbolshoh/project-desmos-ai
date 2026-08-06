<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Roadmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_score',
        'goal_score',
        'estimated_weeks',
        'daily_study_minutes',
        'weekly_plan',
        'monthly_plan',
        'completion_percent',
        'status',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'weekly_plan' => 'array',
            'monthly_plan' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
