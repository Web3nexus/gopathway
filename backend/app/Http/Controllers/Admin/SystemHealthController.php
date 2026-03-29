<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\SecurityLog;
use App\Models\UserActionLog;

class SystemHealthController extends Controller
{
    /**
     * Get system health metrics and real-time status.
     */
    public function index(): JsonResponse
    {
        // 1. Database Health
        $dbStatus = 'healthy';
        $dbLatency = 0;
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $dbLatency = round((microtime(true) - $start) * 1000, 2);
        } catch (\Exception $e) {
            $dbStatus = 'unhealthy';
        }

        // 2. Resource Usage (Memory & CPU)
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->getMemoryLimit();
        $load = sys_getloadavg();
        
        // 3. System Uptime (requires shell_exec or fallback)
        $uptime = 'Unavailable';
        if (function_exists('shell_exec')) {
            $uptime = shell_exec('uptime -p') ?: shell_exec('uptime');
        }

        // 4. API Performance (Simplified metric)
        // In a real app, this would be averaged from a middleware's logs
        $avgResponseTime = 124.5; // ms (mocked for visualization)

        // 5. Error Rates (Last 24 hours)
        $errorsLast24h = SecurityLog::where('severity', 'high')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        // 6. Traffic & Throughout (Last hour)
        $requestsLastHour = UserActionLog::where('created_at', '>=', now()->subHour())->count();

        return response()->json([
            'status' => 'operational',
            'timestamp' => now()->toIso8601String(),
            'metrics' => [
                'uptime' => trim($uptime),
                'cpu_load' => $load,
                'memory' => [
                    'used' => $memoryUsage,
                    'limit' => $memoryLimit,
                    'percentage' => $memoryLimit > 0 ? round(($memoryUsage / $memoryLimit) * 100, 2) : 0,
                ],
                'database' => [
                    'status' => $dbStatus,
                    'latency' => $dbLatency . 'ms',
                    'connection' => config('database.default'),
                ],
                'performance' => [
                    'avg_latency' => $avgResponseTime . 'ms',
                    'throughput' => $requestsLastHour . ' req/hr',
                    'error_rate' => $errorsLast24h > 0 ? round(($errorsLast24h / max(1, $requestsLastHour)) * 100, 2) . '%' : '0%',
                ],
            ],
            'alerts' => $this->getRecentAlerts(),
        ]);
    }

    /**
     * Get memory limit in bytes.
     */
    private function getMemoryLimit()
    {
        $limit = ini_get('memory_limit');
        if ($limit == -1) return 1024 * 1024 * 1024; // Assume 1GB if no limit
        
        $unit = strtolower(substr($limit, -1));
        $bytes = (int) $limit;
        switch ($unit) {
            case 'g': $bytes *= 1024;
            case 'm': $bytes *= 1024;
            case 'k': $bytes *= 1024;
        }
        return $bytes;
    }

    /**
     * Get aggregated system alerts from security and action logs.
     */
    private function getRecentAlerts()
    {
        $logs = SecurityLog::where('severity', 'high')
            ->latest()
            ->limit(5)
            ->get();

        return $logs->map(function ($log) {
            return [
                'id' => $log->id,
                'type' => $log->event_type,
                'severity' => $log->severity,
                'message' => $log->details,
                'timestamp' => $log->created_at->diffForHumans(),
            ];
        });
    }
}
