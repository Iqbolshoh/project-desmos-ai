<?php

namespace App\Services\AiTutor;

use App\Services\AiTutor\Contracts\AiTutorServiceInterface;
use App\Services\AiTutor\DTO\ChatRequestDTO;
use App\Services\AiTutor\DTO\SolveRequestDTO;
use App\Services\AiTutor\DTO\TutorResponseDTO;
use App\Services\AiTutor\DTO\TutorStepDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class ClaudeAiTutorService implements AiTutorServiceInterface
{
    protected string $apiKey;

    protected string $model;

    public function __construct(?string $apiKey = null, string $model = 'claude-3-5-sonnet-20241022')
    {
        $this->apiKey = $apiKey ?? config('services.ai_tutor.anthropic_key', env('ANTHROPIC_API_KEY', ''));
        $this->model = $model;
    }

    public function solve(SolveRequestDTO $request): TutorResponseDTO
    {
        if (empty($this->apiKey)) {
            // Fall back to the mock service if no API key is configured
            $mock = new MockAiTutorService;

            return $mock->solve($request);
        }

        $content = [];

        // Attach the image, if one was provided
        if ($request->imagePath) {
            $imageContent = $this->getImageBase64($request->imagePath);
            if ($imageContent) {
                $content[] = [
                    'type' => 'image',
                    'source' => [
                        'type' => 'base64',
                        'media_type' => $imageContent['media_type'],
                        'data' => $imageContent['data'],
                    ],
                ];
            }
        }

        $prompt = $this->buildPrompt($request->query, ! empty($request->imagePath));
        $content[] = [
            'type' => 'text',
            'text' => $prompt,
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model' => $this->model,
                'max_tokens' => 1500,
                'messages' => [
                    ['role' => 'user', 'content' => $content],
                ],
            ]);

            if (! $response->successful()) {
                Log::error('Claude API Error: '.$response->body());
                throw new RuntimeException('Claude API request failed: '.$response->status());
            }

            $responseData = $response->json();
            $rawText = collect($responseData['content'] ?? [])
                ->where('type', 'text')
                ->pluck('text')
                ->implode("\n");

            return $this->parseResponse($rawText);
        } catch (Throwable $e) {
            Log::error('ClaudeAiTutorService Exception: '.$e->getMessage());
            // Fallback to mock on error
            $mock = new MockAiTutorService;

            return $mock->solve($request);
        }
    }

    public function chatReply(ChatRequestDTO $request): string
    {
        if (empty($this->apiKey)) {
            $mock = new MockAiTutorService;

            return $mock->chatReply($request);
        }

        $messages = [];

        if (! empty($request->history)) {
            foreach ($request->history as $msg) {
                $messages[] = [
                    'role' => $msg['role'] ?? 'user',
                    'content' => $msg['content'] ?? '',
                ];
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => "Sen SAT Math bo'yicha yordamchi o'qituvchisan. O'quvchining savoliga qisqa, tushunarli va o'zbek tilida javob ber: ".$request->message,
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
            ])->timeout(20)->post('https://api.anthropic.com/v1/messages', [
                'model' => $this->model,
                'max_tokens' => 800,
                'messages' => $messages,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return collect($data['content'] ?? [])->where('type', 'text')->pluck('text')->implode("\n");
            }
        } catch (Throwable $e) {
            Log::error('Claude Chat Error: '.$e->getMessage());
        }

        return "Kechirasiz, xatolik yuz berdi. Qayta urinib ko'ring.";
    }

    protected function buildPrompt(string $text, bool $hasImage): string
    {
        $base = $hasImage
            ? 'Rasmda berilgan matematik masalani aniqla va yech.'
            : "Quyidagi masalani yech: \"{$text}\"";

        return <<<PROMPT
Sen SAT Math bo'yicha tajribali o'qituvchi va Desmos grafik kalkulyatori bo'yicha mutaxassissan. {$base}
Faqat quyidagi JSON formatida javob ber, hech qanday qo'shimcha matn yoki markdown belgilarisiz:
{
  "problem": "aniqlangan masala matni",
  "expressions": [
    {"text": "Desmosga aynan shu ko'rinishda yoziladigan ifoda (masalan: y = 2x + 3 yoki (x-2)^2 + (y+1)^2 = 16)", "note": "bu ifoda nimani anglatishi haqidagi qisqa izoh"}
  ],
  "steps": [
    {"title": "1-qadam nomi", "text": "qadam tushuntirishi", "formula": "formula yoki math expression"}
  ],
  "answer": "yakuniy qisqa javob",
  "tip": "Desmosdan SAT paytida foydalanish bo'yicha bitta qisqa maslahat"
}
Ifodalarda albatta Desmos sintaksisidan foydalan (daraja uchun ^, ildiz uchun sqrt(), kasr uchun /). Qadamlar 2 tadan 4 tagacha, qisqa va aniq bo'lsin. Faqat to'g'ridan-to'g'ri JSON qaytar.
PROMPT;
    }

    protected function parseResponse(string $rawText): TutorResponseDTO
    {
        $cleaned = preg_replace('/```json/i', '', $rawText);
        $cleaned = str_replace('```', '', $cleaned);
        $cleaned = trim($cleaned);

        $json = json_decode($cleaned, true);

        if (! $json || ! is_array($json)) {
            return new TutorResponseDTO(
                finalAnswer: 'Yechimni o\'qib bo\'lmadi',
                explanation: $rawText,
                steps: [],
                graphExpression: null
            );
        }

        $steps = [];
        if (isset($json['steps']) && is_array($json['steps'])) {
            foreach ($json['steps'] as $idx => $step) {
                $steps[] = new TutorStepDTO(
                    stepNumber: $idx + 1,
                    title: $step['title'] ?? ('Qadam '.($idx + 1)),
                    explanation: $step['text'] ?? '',
                    mathExpression: $step['formula'] ?? null
                );
            }
        }

        $graphExpr = null;
        if (! empty($json['expressions']) && is_array($json['expressions'])) {
            $firstExpr = $json['expressions'][0];
            $graphExpr = is_array($firstExpr) ? ($firstExpr['text'] ?? null) : $firstExpr;
        }

        $explanation = ($json['problem'] ?? '')."\n\n".($json['tip'] ? '💡 Maslahat: '.$json['tip'] : '');

        return new TutorResponseDTO(
            finalAnswer: $json['answer'] ?? 'Noma\'lum',
            explanation: $explanation,
            steps: $steps,
            graphExpression: $graphExpr
        );
    }

    protected function getImageBase64(string $path): ?array
    {
        try {
            $fullPath = Storage::disk('public')->exists($path)
                ? Storage::disk('public')->path($path)
                : $path;

            if (! file_exists($fullPath)) {
                return null;
            }

            $mime = mime_content_type($fullPath) ?: 'image/jpeg';
            $data = base64_encode(file_get_contents($fullPath));

            return [
                'media_type' => $mime,
                'data' => $data,
            ];
        } catch (Throwable $e) {
            Log::error('Image base64 conversion failed: '.$e->getMessage());

            return null;
        }
    }
}
