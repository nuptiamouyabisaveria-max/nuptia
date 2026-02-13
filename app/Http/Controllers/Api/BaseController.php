<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class BaseController extends \App\Http\Controllers\Controller
{
    /**
     * Send success response
     */
    public function sendSuccess($data = [], $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Send error response
     */
    public function sendError($errors = [], $message = 'Error', $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
