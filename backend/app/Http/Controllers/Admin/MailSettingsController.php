<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Services\MailConfigService;

class MailSettingsController extends Controller
{
    /**
     * Send a test email to the logged-in admin.
     */
    public function testConnection(Request $request)
    {
        $admin = Auth::user();
        
        // Ensure latest settings are applied before testing
        MailConfigService::apply();

        try {
            Mail::raw('This is a test email from GoPathway to verify your SMTP settings.', function ($message) use ($admin) {
                $message->to($admin->email)
                    ->subject('SMTP Test Connection');
            });

            return response()->json([
                'status' => 'success',
                'message' => "Test email sent successfully to {$admin->email}."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }
}
