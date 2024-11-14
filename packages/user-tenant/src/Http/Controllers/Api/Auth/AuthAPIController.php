<?php

namespace Workable\UserTenant\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Enums\ResponseEnum;
use Workable\UserTenant\Http\Requests\LoginRequest;
use Workable\UserTenant\Http\Requests\UserRequest;
use Workable\UserTenant\Http\Resources\LoginResource;
use Workable\UserTenant\Services\AuthService;
use Workable\UserTenant\Services\UserService;

class AuthAPIController extends Controller
{
    use ResponseHelperTrait;

    protected $userService;
    protected $authService;

    public function __construct(
        UserService $userService,
        AuthService $authService
    )
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('login', 'password');

        list(
            'status' => $status,
            'message' => $message,
            'user' => $user,
            ) = $this->authService->login($credentials);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $user);
    }

    public function register(UserRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'user' => $user,
            ) = $this->userService->createUser($request->all());

        $token = JWTAuth::fromUser($user);

        $userRes = new LoginResource($user);

        $userRes->setToken($token);

        return $this->respondSuccess($message, $userRes);
    }
}
