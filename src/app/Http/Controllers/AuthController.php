<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthInvalidCredentials;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\Http;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

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
            $user = $this->userService()->getByEmail($request->email);
            if (!$user instanceof User || !Hash::check($request->password, $user->password)) {
                abort(401);
            }
            $token = auth()->login($user);
            return $this->success([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => env('JWT_DURATION',60) * 60
            ]);
        } catch ( \Throwable $e ) {
            return $this->error($e);
        }  
    }
}