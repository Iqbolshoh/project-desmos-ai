<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SystemStatusController extends Controller
{
    /**
     * Display system status health checks and runtime information.
     */
    public function index(): View
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => ['ok' => true, 'detail' => config('queue.default')],
            'storage' => $this->checkStorage(),
        ];

        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
        ];

        return view('admin.system-status.index', compact('checks', 'info'));
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return ['ok' => true, 'detail' => (string) config('database.default')];
        } catch (\Throwable $e) {
            return ['ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkCache(): array
    {
        try {
            Cache::put('system-status-check', true, 5);

            return ['ok' => Cache::get('system-status-check') === true, 'detail' => (string) config('cache.default')];
        } catch (\Throwable $e) {
            return ['ok' => false, 'detail' => $e->getMessage()];
        }
    }

    private function checkStorage(): array
    {
        $path = storage_path();
        $free = @disk_free_space($path);
        $total = @disk_total_space($path);

        if (!$free || !$total) {
            return ['ok' => false, 'detail' => 'Unable to determine'];
        }

        $usedPercent = round((($total - $free) / $total) * 100);

        return ['ok' => $usedPercent < 90, 'detail' => "{$usedPercent}% used"];
    }
}
