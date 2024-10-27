<?php

namespace App\Http\Controllers;

use App\Enums\UserAccountStatus;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResponseResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class AuthController
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status === UserAccountStatus::INACTIVE->value) {
            throw ValidationException::withMessages([
                'email' => ['The account is inactive. Contact the Admin.'],
            ]);
        }
        return $this->generateToken($user, 'device');
    }

    public function generateToken(User $user, string $device_name): JsonResponse
    {
        $token = $user->createToken($device_name)->plainTextToken;

        return response()->json([
            "token" => $token,
            "email" => $user->email
        ]);
    }

    /**
     * @param RegisterRequest $request
     * @return mixed
     */
    public function register(RegisterRequest $request): mixed
    {
        return DB::transaction(function () use ($request) {
            try {

                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'status' => UserAccountStatus::ACTIVE->value
                ]);

                return $this->generateToken($user, 'device');

            } catch (Exception $exception) {
                Log::error('Registration failed: ' . $exception->getMessage());
                throw new RuntimeException('Something went wrong', 400);
            }
        });
    }


}
