<?php

namespace Workable\ACL\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Workable\ACL\Http\Requests\LoginRequest;
use Workable\ACL\Http\Requests\RegisterRequest;
use Workable\ACL\Http\Resources\UserResource;
use Workable\ACL\Models\UserApi;
use Workable\Support\Traits\ResponseHelperTrait;

class AuthAPIController extends Controller
{
    use ResponseHelperTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->respondError(__('acl::api.token_invalid'));
            }
        } catch (JWTException $e) {
            return $this->respondError(__('acl::api.login_failed'));
        }

        $currentUser = Auth::user();
        $userRes     = new UserResource($currentUser);

        $userRes->setToken($token);

        return $this->respondSuccess(__('acl::api.login_success'), $userRes);
    }

    public function register(RegisterRequest $request)
    {
        $dataUser = $request->only('email', 'password', 'name');

        $user = UserApi::create([
            'name'     => $dataUser['name'],
            'email'    => $dataUser['email'],
            'password' => Hash::make($dataUser['password']),
        ]);

        $token   = JWTAuth::fromUser($user);
        $userRes = new UserResource($user);

        $userRes->setToken($token);

        return $this->respondSuccess( __('acl::api.register_success'), $userRes);
    }
}
