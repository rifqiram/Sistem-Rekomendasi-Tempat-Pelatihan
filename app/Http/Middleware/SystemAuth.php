<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SystemAuth
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Coba autentikasi bawaan (Sanctum) jika sudah ter-login
        if (Auth::guard('sanctum')->check()) {
            Auth::shouldUse('sanctum');

            // Check if user is active
            if (! Auth::user()->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda dinonaktifkan oleh administrator.'
                ], 403);
            }

            return $next($request);
        }

        // 2. Coba autentikasi via static api_token dari kolom tabel_users
        $token = $request->bearerToken();

        if (! $token) {
            $token = $request->input('api_token') ?: $request->header('X-API-Key');
        }

        if ($token && !str_contains($token, '|')) {
            $user = User::where('api_token', $token)->first();
            if ($user) {
                if (! $user->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun Anda dinonaktifkan oleh administrator.'
                    ], 403);
                }

                Auth::login($user);
                $request->setUserResolver(fn () => $user);
                return $next($request);
            }
        }

        // Jika tidak terautentikasi
        return app(\Illuminate\Auth\Middleware\Authenticate::class)->handle($request, function ($req) use ($next) {
            return $next($req);
        }, 'sanctum');
    }
}