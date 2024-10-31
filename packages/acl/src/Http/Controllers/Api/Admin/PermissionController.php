<?php

namespace Workable\ACL\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Workable\ACL\Core\Traits\ApiResponse;
use Workable\ACL\Http\Requests\PermissionRequest;
use Workable\ACL\Http\Resources\PermissionResource;
use Workable\ACL\Services\PermissionService;

class PermissionController extends Controller
{
    use ApiResponse;

    protected $service;

    public function __construct(
        PermissionService $service
    )
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $listPermission = $this->service->getPermissions();
        return $this->successResponse($listPermission);
    }

    public function update(PermissionRequest $request): JsonResponse
    {
        $permission    = $this->service->updatePermission($request->all());
        $permissionRes = new PermissionResource($permission);
        return $this->successResponse($permissionRes);
    }
    public function show($id)
    {
    }
}
