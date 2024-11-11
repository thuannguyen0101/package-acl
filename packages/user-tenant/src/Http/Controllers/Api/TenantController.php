<?php

namespace Workable\UserTenant\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Http\Requests\TenantRequest;
use Workable\UserTenant\Http\Resources\TenantCollection;
use Workable\UserTenant\Http\Resources\TenantResource;
use Workable\UserTenant\Services\TenantService;

class TenantController extends Controller
{
    use ResponseHelperTrait;

    protected $tenantService;

    public function __construct(
        TenantService $tenantService
    )
    {
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'tenants' => $tenants,
            ) = $this->tenantService->getTenants($request->all());

        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondSuccess($message);
        }

        return $this->respondSuccess(
            $message,
            new TenantCollection($tenants)
        );
    }

    public function store(TenantRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'tenant' => $tenant,
            ) = $this->tenantService->createTenant($request->all());

        return $this->baseResponse($status, $message, $tenant);
    }

    public function update(int $id, TenantRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'tenant' => $tenant,
            ) = $this->tenantService->updateTenant($id, $request->all());

        return $this->baseResponse($status, $message, $tenant);
    }

    public function show(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            'tenant' => $tenant,
            ) = $this->tenantService->getTenant($id);

        return $this->baseResponse($status, $message, $tenant);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            'tenant' => $tenant,
            ) = $this->tenantService->deleteTenant($id);

        return $this->baseResponse($status, $message, $tenant);
    }

    private function baseResponse($status, $message, $tenant)
    {
        if ($status != ResponseMessageEnum::CODE_OK) {
            return $this->respondError($message);
        }

        if ($tenant) {
            $tenant = new TenantResource($tenant);
        }

        return $this->respondSuccess($message, $tenant);
    }
}
