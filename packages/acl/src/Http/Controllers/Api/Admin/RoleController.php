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
        $listRole = $this->roleService->getRoles($request->all());

        if ($listRole->count() === 0) {
            return $this->respondError(__('acl::api.no_data'));
        }

        $listRole = new RoleCollection($listRole);

        return $this->respondSuccess(
            __('acl::api.success'),
            $listRole
        );
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

        return $this->respondSuccess(
            __('acl::api.created'),
            new RoleResource($role)
        );
    }

    public function show(int $id)
    {
        $role = $this->roleService->getRole($id);

        if (empty($role)) {
            return $this->respondError(__('acl::api.not_found'));
        }

        return $this->respondSuccess(
            __('acl::api.success'),
            new RoleResource($role)
        );
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

        $roleRes = new RoleResource($role);

        return $this->respondSuccess(
            __('acl::api.updated'),
            new RoleResource($roleRes)
        );
    }

    public function destroy(int $id)
    {
        $deleted = $this->roleService->deleteRole($id);

        if (!$deleted) {
            return $this->respondError(__('acl::api.not_found'));
        }

        return $this->respondSuccess(
            __('acl::api.deleted'),
        );
    }

    public function assignRoleForModel(RoleAssignModelRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'data' => $user
            ) = $this->roleService->assignRoleForModel($request->model_id, $request->role_id);

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess(
            __('acl::api.updated'),
            new UserResource($user)
        );
    }
}
