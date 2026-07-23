<?php

namespace App\Services\AiTutor;

use App\Services\AiTutor\Contracts\AiTutorServiceInterface;
use App\Services\AiTutor\DTO\ChatRequestDTO;
use App\Services\AiTutor\DTO\SolveRequestDTO;
use App\Services\AiTutor\DTO\TutorResponseDTO;
use App\Services\AiTutor\DTO\TutorStepDTO;

class MockAiTutorService implements AiTutorServiceInterface
{
    public function solve(SolveRequestDTO $request): TutorResponseDTO
    {
        $query = strtolower($request->query);

        if (str_contains($query, 'kvadrat tenglama')) {
            return new TutorResponseDTO(
                finalAnswer: 'x1 = 2, x2 = -2',
                explanation: 'Berilgan tenglamani yechish uchun kvadrat ildizdan foydalanamiz.',
                steps: [
                    new TutorStepDTO(1, 'Tenglamani yozish', 'Tenglamani standart ko\'rinishda yozamiz:', 'x^2 - 4 = 0'),
                    new TutorStepDTO(2, 'O\'zgartirish', '4 ni o\'ng tomonga o\'tkazamiz.', 'x^2 = 4'),
                    new TutorStepDTO(3, 'Ildiz olish', 'Ikkala tomondan kvadrat ildiz olamiz.', 'x = \pm 2'),
                ],
                graphExpression: 'y = x^2 - 4'
            );
        }

        if (str_contains($query, 'chiziq') || str_contains($query, 'chiziqli')) {
            return new TutorResponseDTO(
                finalAnswer: 'y = 2x + 1',
                explanation: 'Bu to\'g\'ri chiziq tenglamasi. U y o\'qini 1 da kesib o\'tadi va burchak koeffitsienti 2 ga teng.',
                steps: [
                    new TutorStepDTO(1, 'Tenglamani tahlil qilish', 'Standart ko\'rinish: y = mx + c', 'm=2, c=1'),
                ],
                graphExpression: 'y = 2x + 1'
            );
        }

        if (str_contains($query, 'aylana')) {
            return new TutorResponseDTO(
                finalAnswer: 'r = 5',
                explanation: 'Aylana markazi (0,0) nuqtada joylashgan va radiusi 5 ga teng.',
                steps: [
                    new TutorStepDTO(1, 'Tenglamani tanib olish', 'Standart aylana tenglamasi:', 'x^2 + y^2 = r^2'),
                    new TutorStepDTO(2, 'Radiusni topish', 'r^2 = 25 bo\'lsa, r = 5 bo\'ladi.', 'r = 5'),
                ],
                graphExpression: 'x^2 + y^2 = 25'
            );
        }

        // Default response
        return new TutorResponseDTO(
            finalAnswer: 'Noma\'lum',
            explanation: 'Sizning so\'rovingizni tushuna olmadim. Iltimos, "kvadrat tenglama", "chiziq" yoki "aylana" haqida so\'rab ko\'ring.',
            steps: [],
            graphExpression: null
        );
    }

    public function chatReply(ChatRequestDTO $request): string
    {
        $message = strtolower($request->message);

        if (str_contains($message, 'rahmat')) {
            return 'Arzimaydi! Yana qanday savollaringiz bor?';
        }

        if (str_contains($message, 'nega')) {
            return 'Chunki SAT imtihonida bunday masalalarni tez yechish usuli hisoblanadi. Formulani yodlab oling.';
        }

        return 'Tushunmadim, qaytadan tushuntirib bera olasizmi?';
    }
}
