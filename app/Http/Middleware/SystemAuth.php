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
            return $next($request);
        }

        // 2. Coba autentikasi via static api_token dari kolom tabel_users
        $token = $request->bearerToken();

        if (! $token) {
            $token = $request->input('api_token') ?: $request->header('X-API-Key');
        }

        // Jika bearer token berbentuk token Sanctum (ada karakter '|') tapi tidak valid di check pertama,
        // jangan cocokkan dengan api_token statis. Tapi jika token tidak mengandung '|',
        // cocokkan dengan kolom api_token.
        if ($token && !str_contains($token, '|')) {
            $user = User::where('api_token', $token)->first();
            if ($user) {
                Auth::login($user);
                $request->setUserResolver(fn () => $user);
                return $next($request);
            }
        }

        // Jika tidak terautentikasi, gunakan default Laravel Authenticate middleware dengan guard sanctum
        return app(\Illuminate\Auth\Middleware\Authenticate::class)->handle($request, function ($req) use ($next) {
            return $next($req);
        }, 'sanctum');
    }
}
