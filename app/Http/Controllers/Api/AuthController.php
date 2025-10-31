<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
       try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse('Invalid credentials', 401);
            }
            $user->tokens()->delete();
            $token = $user->createToken('API Token')->plainTextToken;

            $data = [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ];

            return $this->successResponse($data, 'Login successful');

        } catch (Throwable $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return $this->errorResponse('Something went wrong during login.', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return $this->successResponse([], 'Logged out successfully');
        } catch (Throwable $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return $this->errorResponse('Something went wrong during logout.', 500);
        }
    }
}
