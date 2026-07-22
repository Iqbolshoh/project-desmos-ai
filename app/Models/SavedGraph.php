<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedGraph extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ai_tutor_session_id',
        'title',
        'desmos_state',
        'thumbnail_path',
    ];

    protected function casts(): array
    {
        return [
            'desmos_state' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function aiTutorSession(): BelongsTo
    {
        return $this->belongsTo(AiTutorSession::class);
    }
}
