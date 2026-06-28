<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Admin login — validates credentials and returns a Sanctum token.
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $key = 'login_admin_' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
            ], 429);
        }

        $user = User::where('email', $request->email)
                    ->whereIn('role', ['admin', 'inventory', 'sales'])
                    ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        RateLimiter::clear($key);

        // Revoke old tokens and issue a fresh one
        $user->tokens()->delete();
        $token = $user->createToken('admin-panel')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * Logout — revoke current token.
     */
    public function adminLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
