<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Exception;

class HealthController extends Controller
{
    /**
     * Check the health of the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        $status = [
            'api' => 'up',
            'database' => 'up',
            'mail' => 'up',
            'timestamp' => now()->toIso8601String(),
            'environment' => app()->environment(),
        ];

        // Check Database
        try {
            DB::connection()->getPdo();
        } catch (Exception $e) {
            $status['database'] = 'down';
        }

        // Check Mail (Optional bit of checking if config is set)
        if (empty(config('mail.mailers.smtp.host'))) {
            $status['mail'] = 'degraded';
        }

        $overallStatus = 'operational';
        if ($status['database'] === 'down') {
            $overallStatus = 'down';
        } elseif ($status['mail'] === 'degraded') {
            $overallStatus = 'degraded';
        }

        return response()->json([
            'status' => $overallStatus,
            'checks' => $status,
            'version' => '1.0.0'
        ], $overallStatus === 'down' ? 503 : 200);
    }
}
