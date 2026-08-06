<?php

namespace App\Services\AiTutor\DTO;

class TutorResponseDTO
{
    /**
     * @param  TutorStepDTO[]  $steps
     * @param  string|null  $graphExpression  Desmos LaTeX expression
     */
    public function __construct(
        public readonly string $finalAnswer,
        public readonly string $explanation,
        public readonly array $steps = [],
        public readonly ?string $graphExpression = null
    ) {}
}
