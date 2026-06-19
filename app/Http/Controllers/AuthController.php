<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Email atau password salah', 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'Login berhasil');
    }

    public function me(Request $request)
    {
        return $this->successResponse([
            'user' => new UserResource($request->user()),
        ], 'Data user berhasil diambil');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tabel_users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,user,pencari_kerja',
        ]);

        $requestedRole = $data['role'] ?? 'user';
        $role = in_array($requestedRole, ['user', 'pencari_kerja'], true) ? 'user' : 'user';

        if ($request->user()?->role === 'admin' && $requestedRole === 'admin') {
            $role = 'admin';
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $role,
            'api_token' => \Illuminate\Support\Str::random(60),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'Register berhasil', 201);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->successResponse(null, 'Logout berhasil');
    }
}
