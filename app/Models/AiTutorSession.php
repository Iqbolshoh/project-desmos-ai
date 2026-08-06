<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiTutorSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic_id',
        'input_type',
        'input_text',
        'input_image_path',
        'ai_response',
        'desmos_state',
        'driver',
    ];

    protected function casts(): array
    {
        return [
            'ai_response' => 'array',
            'desmos_state' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function savedGraphs(): HasMany
    {
        return $this->hasMany(SavedGraph::class);
    }
}
