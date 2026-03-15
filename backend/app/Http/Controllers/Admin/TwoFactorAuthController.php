<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TwoFactorAuthController extends Controller
{
    public function generateSecret(Request $request)
    {
        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');

        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        return response()->json([
            'secret' => $user->two_factor_secret,
            'qr_code_url' => $qrCodeUrl,
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valid) {
            $user->two_factor_enabled = true;
            $user->save();

            return response()->json([
                'message' => '2FA enabled successfully',
            ]);
        }

        return response()->json([
            'message' => 'Invalid verification code',
        ], 422);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password',
            ], 422);
        }

        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->save();

        return response()->json([
            'message' => '2FA disabled successfully',
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valid) {
            // Store verification in session
            $request->session()->put('2fa_verified', true);

            return response()->json([
                'message' => '2FA code verified',
            ]);
        }

        return response()->json([
            'message' => 'Invalid verification code',
        ], 422);
    }
}
