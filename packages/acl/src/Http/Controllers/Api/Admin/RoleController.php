<?php

namespace Workable\ACL\Http\Controllers\Api\Admin;

use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Http\Requests\RoleAssignModelRequest;
use Workable\ACL\Http\Requests\RoleListRequest;
use Workable\ACL\Http\Requests\RoleRequest;
use Workable\ACL\Http\Resources\Role\RoleCollection;
use Workable\ACL\Http\Resources\Role\RoleResource;
use Workable\ACL\Http\Resources\UserResource;
use Workable\ACL\Services\RoleService;
use Workable\Support\Traits\ResponseHelperTrait;

class RoleController
{
    use ResponseHelperTrait;

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(RoleListRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'roles' => $roles,
            ) = $this->roleService->getRoles($request->all());

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondSuccess(
                $message,
                $roles
            );
        }

        return $this->respondSuccess($message, new RoleCollection($roles));
    }

    public function store(RoleRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'role' => $role
            ) = $this->roleService->createRole($request->all());

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, new RoleResource($role));
    }

    public function show(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            'role' => $role
            ) = $this->roleService->getRole($id);

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, new RoleResource($role));
    }

    public function update(int $id, RoleRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'role' => $role
            ) = $this->roleService->updateRole($id, $request->all());

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, new RoleResource($role));
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->roleService->deleteRole($id);

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }

    public function assignRoleForModel(RoleAssignModelRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'user' => $user
            ) = $this->roleService->assignRoleForModel($request->model_id, $request->role_id);

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, new UserResource($user));
    }
}
