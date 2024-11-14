<?php

namespace Workable\UserTenant\Services;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Workable\UserTenant\Enums\ResponseEnum;
use Workable\UserTenant\Http\Resources\LoginResource;

class AuthService
{
    public function login(array $request): array
    {
        try {
            $loginType = filter_var($request['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $credentials = [
                $loginType => $request['login'] ?? '',
                'password' => $request['password'] ?? '',
            ];

            if (!$token = JWTAuth::attempt($credentials)) {
                return [
                    'status'  => ResponseEnum::CODE_UNAUTHORIZED,
                    'message' => __('user-tenant::api.auth.login_failed'),
                    'user'    => null
                ];
            }
        } catch (JWTException $e) {
            return [
                'status'  => ResponseEnum::CODE_INTERNAL_SERVER_ERROR,
                'message' => __('user-tenant::api.auth.server_error'),
                'user'    => null
            ];
        }

        $currentUser = Auth::user();
        $userRes     = new LoginResource($currentUser);

        $userRes->setToken($token);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.auth.login_success'),
            'user'    => $userRes
        ];
    }
}
