<?php

namespace Workable\ACL\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Http\Requests\PermissionListRequest;
use Workable\ACL\Http\Resources\Permission\PermissionCollection;
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
        list(
            'status' => $status,
            'message' => $message,
            'permissions' => $permissions,
            ) = $this->permissionService->getPermissions($request->all());

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondSuccess(
                $message,
                $permissions
            );
        }

        return $this->respondSuccess($message, new PermissionCollection($permissions));
    }
}
