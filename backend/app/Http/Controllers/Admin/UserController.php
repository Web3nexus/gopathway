<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * List all users with their subscription status.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with(['roles', 'subscriptions.plan']);

        if ($request->filled('role') && $request->role !== 'all') {
            $query->role($request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(50);

        return response()->json($users);
    }

    /**
     * Manually grant premium status to a user.
     */
    public function grantPremium(Request $request, User $user): JsonResponse
    {
        $premiumPlan = SubscriptionPlan::where('is_active', true)
            ->where('price', '>', 0)
            ->orderBy('price', 'desc')
            ->first();

        if (!$premiumPlan) {
            return response()->json(['message' => 'No premium plan found in database.'], 422);
        }

        // Days can be provided, default to 1 year (365 days) if not specified or "lifetime" (10 years)
        $days = $request->input('days');
        $endsAt = $days === 'lifetime' ? now()->addYears(10) : now()->addDays(intval($days ?: 365));

        $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $premiumPlan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $endsAt,
            'payment_status' => 'completed',
        ]);

        return response()->json([
            'message' => "Premium status granted to {$user->name} successfully.",
        ]);
    }

    /**
     * Manually remove premium status from a user.
     */
    public function removePremium(User $user): JsonResponse
    {
        $user->subscriptions()->where('status', 'active')->update([
            'status' => 'cancelled',
            'ends_at' => now()
        ]);

        return response()->json([
            'message' => "Premium status removed from {$user->name} successfully.",
        ]);
    }

    /**
     * Start an impersonation session.
     *
     * Uses stateful Sanctum (session/cookie). Stores admin ID in session,
     * then logs in as the target user via the web guard.
     */
    public function impersonate(Request $request, User $user): JsonResponse
    {
        // Get current admin from the request (stateful Sanctum sets $request->user())
        $admin = $request->user();

        if (!$admin) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->hasRole('admin')) {
            return response()->json(['message' => 'Cannot impersonate another admin.'], 403);
        }

        // Store admin ID in session for restoration
        session(['admin_impersonator' => $admin->id]);

        // Switch the session to the target user. 
        // We use the 'web' guard explicitly. If it's being proxied by Sanctum to a RequestGuard,
        // we can try to access the underlying session-based guard or use the AuthManager.
        $guard = Auth::guard('web');

        if (method_exists($guard, 'login')) {
            $guard->login($user);
        }
        else {
            // Fallback for cases where the guard is proxied/replaced (e.g. by Sanctum)
            Auth::login($user);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => $user->load('roles'),
            'message' => "Now impersonating {$user->name}.",
        ]);
    }

    /**
     * End the impersonation session and restore the admin account.
     */
    public function leaveImpersonation(Request $request): JsonResponse
    {
        $adminId = session('admin_impersonator');

        if (!$adminId) {
            return response()->json(['message' => 'No active impersonation session.'], 404);
        }

        $admin = User::find($adminId);

        if (!$admin) {
            return response()->json(['message' => 'Original admin account not found.'], 404);
        }

        // Clear the impersonation flag
        session()->forget('admin_impersonator');

        // Log back in as admin
        $guard = Auth::guard('web');
        if (method_exists($guard, 'login')) {
            $guard->login($admin);
        }
        else {
            Auth::login($admin);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => $admin->load('roles'),
            'message' => 'Returned to admin session successfully.',
        ]);
    }
}