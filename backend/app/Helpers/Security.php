<?php

namespace App\Helpers;

use App\Models\SecurityLog;
use Illuminate\Support\Facades\Request;

class Security
{
    /**
     * Log a security event.
     *
     * @param string $eventType
     * @param string $severity (low, medium, high, critical)
     * @param string|null $details
     * @param array|null $payload
     * @param int|null $userId
     * @return void
     */
    public static function log(string $eventType, string $severity = 'low', ?string $details = null, ?array $payload = null, ?int $userId = null): void
    {
        SecurityLog::create([
            'user_id' => $userId ?: (auth()->check() ? auth()->id() : null),
            'event_type' => $eventType,
            'severity' => $severity,
            'details' => $details,
            'payload' => $payload,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
        ]);

        if (in_array($severity, ['high', 'critical'])) {
            \Log::emergency("SECURITY ALERT: [{$severity}] {$eventType} - {$details}", [
                'ip' => Request::ip(),
                'user_id' => $userId ?: (auth()->check() ? auth()->id() : null),
                'url' => Request::fullUrl(),
            ]);
        }
    }
}