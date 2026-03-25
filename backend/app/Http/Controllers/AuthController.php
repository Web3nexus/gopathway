<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Mail\DynamicEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $referrer = null;
        if ($request->ref) {
            $referrer = User::where('referral_code', $request->ref)->first();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referred_by_id' => $referrer?->id,
        ]);

        // Assign default role based on Spatie Permissions setup
        $user->assignRole('user');

        // Send Welcome Email
        try {
            Mail::to($user->email)->send(new DynamicEmail('welcome_email', [
                'user_name' => $user->name,
                'dashboard_url' => config('app.frontend_url') . '/dashboard',
                'upgrade_url' => config('app.frontend_url') . '/pricing',
            ]));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
        }

        // Trigger Email Verification
        $user->sendEmailVerificationNotification();

        Auth::guard('web')->login($user);

        return response()->json([
            'message' => 'Registration successful',
            'user' => clone $user->load('roles'),
            'is_impersonating' => false
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            if ($user->two_factor_enabled) {
                return response()->json([
                    'message' => '2FA code required',
                    'two_factor_required' => true,
                    'user' => $user->load('roles') // Still return user info for UI
                ]);
            }

            $request->session()->regenerate();

            // Send Login Notification Email
            try {
                Mail::to($user->email)->send(new DynamicEmail('login_notification', [
                    'user_name' => $user->name,
                    'device' => $request->header('User-Agent'),
                    'time' => now()->format('M d, Y H:i:s'),
                    'dashboard_url' => config('app.frontend_url') . '/dashboard',
                ]));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send login notification to {$user->email}: " . $e->getMessage());
            }

            return response()->json([
                'message' => 'Login successful',
                'user' => $user->load('roles'),
                'is_impersonating' => false
            ]);
        }

        \App\Helpers\Security::log('login_failed', 'medium', "Failed login attempt for email: {$request->email}", ['email' => $request->email]);

        return response()->json([
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('roles');
        $isAdmin = $user->roles->contains('name', 'admin');

        return response()->json([
            'user' => $user,
            // Admins cannot be impersonating themselves; only non-admins can be impersonated
            'is_impersonating' => !$isAdmin && session()->has('admin_impersonator'),
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password changed successfully.']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()->load('roles')
        ]);
    }
}