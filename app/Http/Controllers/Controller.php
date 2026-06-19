<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function successResponse($data = null, string $message = 'Berhasil', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(string $message = 'Gagal', int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function authorizeAdmin(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'admin') {
            return $this->errorResponse('Forbidden', 403);
        }

        return null;
    }
}
