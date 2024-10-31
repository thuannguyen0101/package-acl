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

class AuthAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Thông tin đăng nhập không hợp lệ'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Không thể tạo token'], 500);
        }
        $currentUser = Auth::user();


        $userRes = new UserResource($currentUser);
        $userRes->setToken($token);
        $userRes->setMessage("Login successful");

        return $userRes;
    }

    public function register(RegisterRequest $request): UserResource
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
        $userRes->setMessage("User registered successfully");

        return $userRes;
    }
}
