<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => User::ROLE_OBSERVER,
            ]);

            return $this->sendSuccess([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 'User registered successfully', 201);
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), 'Validation failed', 422);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($validated)) {
                return $this->sendError([], 'Invalid credentials', 401);
            }

            $user = User::where('email', $validated['email'])->first();

            return $this->sendSuccess([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 'Logged in successfully');
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), 'Validation failed', 422);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendSuccess([], 'Logged out successfully');
    }

    /**
     * Get current user
     */
    public function me(Request $request): JsonResponse
    {
        return $this->sendSuccess($request->user(), 'Current user retrieved');
    }
}
