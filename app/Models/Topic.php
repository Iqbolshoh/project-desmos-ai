<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'domain',
        'description',
        'icon',
        'sort_order',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function aiTutorSessions(): HasMany
    {
        return $this->hasMany(AiTutorSession::class);
    }
}
