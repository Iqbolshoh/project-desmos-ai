<?php

namespace App\Services\AiTutor\Contracts;

use App\Services\AiTutor\DTO\ChatRequestDTO;
use App\Services\AiTutor\DTO\SolveRequestDTO;
use App\Services\AiTutor\DTO\TutorResponseDTO;

interface AiTutorServiceInterface
{
    /**
     * Solve a math problem and return steps with an optional graph.
     */
    public function solve(SolveRequestDTO $request): TutorResponseDTO;

    /**
     * Reply to a chat message within a session context.
     */
    public function chatReply(ChatRequestDTO $request): string;
}
