<?php

namespace Workable\ACL\Http\Controllers\Api\Admin;

use Workable\ACL\Core\Traits\ApiResponse;
use Workable\ACL\Http\Requests\RoleRequest;
use Workable\ACL\Http\Resources\RoleResource;
use Workable\ACL\Services\RoleService;

class RoleController
{
    use ApiResponse;

    protected $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $listRole = $this->service->getRoles();
        return $this->successResponse(RoleResource::collection($listRole));
    }

    public function show(int $id)
    {
        $role = $this->service->show($id);
        if (empty($role)) {
            return $this->errorResponse('Role not found', 404);
        }
        return $this->successResponse(new RoleResource($role));
    }

    public function store(RoleRequest $request)
    {
        $role = $this->service->createRole($request->all());
        if (empty($role)) {
            return $this->errorResponse('Role not created', 500);
        }
        $roleRes = new RoleResource($role);

        return $this->successResponse($roleRes);
    }

    public function save(RoleRequest $request)
    {
        $role    = $this->service->updateRole($request->all());
        $roleRes = new RoleResource($role);
        return $this->successResponse(new RoleResource($roleRes), "Updated successfully");
    }

    public function destroy()
    {

    }
}
