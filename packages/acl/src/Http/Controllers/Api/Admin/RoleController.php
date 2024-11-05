<?php

namespace Workable\ACL\Http\Controllers\Api\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Http\Requests\RoleAssignModelRequest;
use Workable\ACL\Http\Requests\RoleRequest;
use Workable\ACL\Http\Resources\Role\RoleCollection;
use Workable\ACL\Http\Resources\Role\RoleResource;
use Workable\ACL\Http\Resources\UserResource;
use Workable\ACL\Services\RoleService;

class RoleController
{
    use ApiResponseTrait;

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters  = $request->get('filters', []);
        $listRole = $this->roleService->getRoles($filters);

        if ($listRole->count() === 0) {
            return $this->successResponse([], "Không có dữ liệu.", ResponseMessageEnum::CODE_NO_CONTENT);
        }

        $listRole = new RoleCollection($listRole);

        return $this->successResponse($listRole);
    }

    public function store(RoleRequest $request): JsonResponse
    {
        list(
            'status' => $status,
            'message' => $message,
            'role' => $role) = $this->roleService->createRole($request->all());

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->errorResponse($message, $status);
        }

        return $this->createdResponse(new RoleResource($role));
    }

    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->getRole($id);

        if (empty($role)) {
            return $this->notFoundResponse();
        }

        return $this->successResponse(new RoleResource($role));
    }

    public function update(int $id, RoleRequest $request): JsonResponse
    {
        list(
            'status' => $status,
            'message' => $message,
            'role' => $role) = $this->roleService->updateRole($id, $request->all());

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->errorResponse($message, $status);
        }

        $roleRes = new RoleResource($role);

        return $this->updatedResponse(new RoleResource($roleRes));
    }

    public function destroy(int $id)
    {
        $deleted = $this->roleService->deleteRole($id);

        if (!$deleted) {
            return $this->notFoundResponse();
        }

        return $this->deletedResponse();
    }

    public function assignRoleForModel(RoleAssignModelRequest $request): JsonResponse
    {
        list(
            'status' => $status,
            'message' => $message,
            'data' => $user) = $this->roleService->assignRoleForModel($request->model_id, $request->role_id);

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->errorResponse($message, $status);
        }

        return $this->updatedResponse(new UserResource($user));
    }
}
