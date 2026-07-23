<?php

namespace App\Services\AiTutor\DTO;

class ChatRequestDTO
{
    public function __construct(
        public readonly string $message,
        public readonly int $sessionId,
        public readonly ?array $history = []
    ) {
    }
}
