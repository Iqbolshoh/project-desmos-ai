<?php

namespace App\Services\AiTutor\DTO;

class SolveRequestDTO
{
    public function __construct(
        public readonly string $query,
        public readonly ?string $imagePath = null,
        public readonly ?int $topicId = null
    ) {
    }
}
