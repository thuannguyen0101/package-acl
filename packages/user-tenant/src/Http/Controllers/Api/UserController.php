<?php

namespace Workable\UserTenant\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Enums\ResponseEnum;
use Workable\UserTenant\Http\Requests\UserRequest;
use Workable\UserTenant\Http\Resources\UserCollection;
use Workable\UserTenant\Http\Resources\UserResource;
use Workable\UserTenant\Services\UserService;

class UserController extends Controller
{
    use ResponseHelperTrait;

    protected $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'users' => $users,
            ) = $this->userService->getUsers($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondSuccess($message);
        }

        return $this->respondSuccess(
            $message,
            new UserCollection($users)
        );
    }

    public function show(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            'user' => $user,
            ) = $this->userService->getUser($id);

        return $this->baseResponse($status, $message, $user);
    }

    public function store(UserRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'user' => $user,
            ) = $this->userService->createUser($request->all());

        return $this->baseResponse($status, $message, $user);
    }

    public function update(int $id, UserRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'user' => $user,
            ) = $this->userService->updateUser($id, $request->all());

        return $this->baseResponse($status, $message, $user);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            'user' => $user,
            ) = $this->userService->deleteUser($id);

        return $this->baseResponse($status, $message, $user);
    }

    private function baseResponse($status, $message, $user)
    {
        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        if ($user) {
            $user = new UserResource($user);
        }

        return $this->respondSuccess($message, $user);
    }
}
