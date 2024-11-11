<?php

namespace Workable\UserTenant\Services;

use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Services\BaseService;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Models\Tenant;

class TenantService extends BaseService
{
    use FilterBuilderTrait;

    public function getTenants($request): array
    {
        $filter = $this->getFilterRequest($request);
        $query  = Tenant::query();

        if (!empty($filter['with'])) {
            $query->with($filter['with']);
        }

        $listTenant = $query->get();

        if ($listTenant->count() === 0) {
            return [
                'status'  => ResponseMessageEnum::CODE_NO_CONTENT,
                'message' => __('acl::api.no_data'),
                'tenants' => $listTenant,
            ];
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.success'),
            'tenants' => $listTenant,
        ];
    }

    public function getTenant($id): array
    {
        $tenant = Tenant::query()->find($id);

        if (!$tenant) {
            return [
                'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                'message' => __('acl::api.not_found'),
                'tenant'  => $tenant,
            ];
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.updated'),
            'tenant'  => $tenant,
        ];
    }

    public function createTenant($data): array
    {
        $data['status'] = TenantEnum::STATUS_ACTIVE;

        $tenant = Tenant::query()->create($data);

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.created'),
            'tenant'  => $tenant,
        ];
    }

    public function updateTenant($id, $data): array
    {
        $tenant = Tenant::query()->find($id);

        if (!$tenant) {
            return [
                'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                'message' => __('acl::api.not_found'),
                'tenant'  => $tenant,
            ];
        }
        $tenant->fill($data);

        if ($tenant->isDirty()) {
            $tenant->update();
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.updated'),
            'tenant'  => $tenant,
        ];
    }

    public function deleteTenant($id): array
    {
        $tenant = Tenant::query()->find($id);

        if (!$tenant) {
            return [
                'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                'message' => __('acl::api.not_found'),
                'tenant'  => $tenant,
            ];
        }

        $tenant->delete();

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.deleted'),
            'tenant'  => null,
        ];
    }
}
