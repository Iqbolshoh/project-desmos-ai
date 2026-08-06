<?php

namespace App\Services\AiTutor\DTO;

class TutorStepDTO
{
    public function __construct(
        public readonly int $stepNumber,
        public readonly string $title,
        public readonly string $explanation,
        public readonly ?string $mathExpression = null
    ) {}
}
