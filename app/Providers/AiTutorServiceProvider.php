<?php

namespace App\Providers;

use App\Services\AiTutor\ClaudeAiTutorService;
use App\Services\AiTutor\Contracts\AiTutorServiceInterface;
use App\Services\AiTutor\MockAiTutorService;
use Illuminate\Support\ServiceProvider;

class AiTutorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AiTutorServiceInterface::class, function ($app) {
            $driver = config('services.ai_tutor.driver', 'claude');

            if ($driver === 'mock') {
                return new MockAiTutorService;
            }

            return new ClaudeAiTutorService;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
