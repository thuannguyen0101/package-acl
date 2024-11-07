<?php

namespace Workable\ACL\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Workable\ACL\Http\Requests\PermissionListRequest;
use Workable\ACL\Http\Requests\PermissionRequest;
use Workable\ACL\Http\Resources\Permission\PermissionCollection;
use Workable\ACL\Http\Resources\Permission\PermissionResource;
use Workable\ACL\Services\PermissionService;
use Workable\Support\Traits\ResponseHelperTrait;

class PermissionController extends Controller
{
    use ResponseHelperTrait;

    protected $permissionService;

    public function __construct(
        PermissionService $permissionService
    )
    {
        $this->permissionService = $permissionService;
    }

    public function index(PermissionListRequest $request)
    {
        $listPermission = $this->permissionService->getPermissions($request->all());

        if ($listPermission->count() === 0) {
            return $this->respondError(__('acl::api.no_data'));
        }

        $listPermission = new PermissionCollection($listPermission);

        return $this->respondSuccess(
            __('acl::api.success'),
            $listPermission,
        );
    }

    public function update(int $id, PermissionRequest $request)
    {
        $permission = $this->permissionService->updatePermission($id, $request->all());

        if (!$permission) {
            return $this->respondError(__('acl::api.not_found'));
        }

        $permissionRes = new PermissionResource($permission);

        return $this->respondSuccess(
            __('acl::api.updated'),
            $permissionRes
        );
    }

    public function show(int $id)
    {
        $permission = $this->permissionService->getPermission($id);

        if (!$permission) {
            return $this->respondError(__('acl::api.not_found'));
        }
        $permissionRes = new PermissionResource($permission);

        return $this->respondSuccess(
            __('acl::api.success'),
            $permissionRes
        );
    }
}
