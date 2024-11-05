<?php

namespace Workable\ACL\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Http\Requests\PermissionRequest;
use Workable\ACL\Http\Resources\Permission\PermissionCollection;
use Workable\ACL\Http\Resources\Permission\PermissionResource;
use Workable\ACL\Services\PermissionService;

class PermissionController extends Controller
{
    use ApiResponseTrait;

    protected $service;

    public function __construct(
        PermissionService $service
    )
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->get('filters', []);

        $listPermission = $this->service->getPermissions($filters);
        if ($listPermission->count() === 0) {
            return $this->successResponse([], "Không có dữ liệu.", ResponseMessageEnum::CODE_NO_CONTENT);
        }

        $listPermission = new PermissionCollection($listPermission);

        return $this->successResponse($listPermission);
    }

    public function update(int $id, PermissionRequest $request): JsonResponse
    {
        $permission = $this->service->updatePermission($id, $request->all());

        if (!$permission) {
            return $this->notFoundResponse();
        }

        $permissionRes = new PermissionResource($permission);

        return $this->updatedResponse($permissionRes);
    }

    public function show(int $id): JsonResponse
    {
        $permission = $this->service->getPermission($id);

        if (!$permission) {
            return $this->notFoundResponse();
        }
        $permissionRes = new PermissionResource($permission);

        return $this->successResponse($permissionRes);
    }
}
