<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthInvalidCredentials;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\Http;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    use Http;

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): string
    {
        try {
            return $this->success([
                'access_token' => auth('api')->attempt([
                    "email" => $request->email,
                    "password" => $request->password,
                ]),
                'token_type' => 'bearer',
                'expires_in' => env('JWT_DURATION',60) * 60
            ]);
        } catch ( \Throwable $e ) {
            return $this->error($e);
        }  
    }
}