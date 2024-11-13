<?php

namespace Workable\UserTenant\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Http\Requests\LoginRequest;
use Workable\UserTenant\Http\Requests\UserRequest;
use Workable\UserTenant\Http\Resources\LoginResource;
use Workable\UserTenant\Services\UserService;

class AuthAPIController extends Controller
{
    use ResponseHelperTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->respondError(__('user-tenant::api.auth.login_failed'));
            }
        } catch (JWTException $e) {
            return $this->respondError(__('user-tenant::api.auth.server_error'));
        }

        $currentUser = Auth::user();
        $userRes     = new LoginResource($currentUser);

        $userRes->setToken($token);

        return $this->respondSuccess(__('user-tenant::api.auth.login_success'), $userRes);
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
