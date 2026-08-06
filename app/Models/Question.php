<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'type',
        'difficulty',
        'is_diagnostic',
        'prompt',
        'image_path',
        'options',
        'correct_answer',
        'explanation',
        'desmos_expressions',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'is_diagnostic' => 'boolean',
            'options' => 'array',
            'desmos_expressions' => 'array',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuestionAttempt::class);
    }
}
