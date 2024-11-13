<?php

namespace Workable\UserTenant\Services;

use Workable\Support\Traits\FilterBuilderTrait;
use Workable\UserTenant\Enums\ResponseEnum;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Models\Tenant;

class TenantService
{
    use FilterBuilderTrait;

    public function getTenants($request): array
    {
        $filter = $this->getFilterRequest($request);

        $query = Tenant::query()->where('id', get_tenant_id());

        if (!empty($filter['with'])) {
            $query->with($filter['with']);
        }

        $listTenant = $query->get();

        if ($listTenant->count() === 0) {
            return [
                'status'  => ResponseEnum::CODE_NO_CONTENT,
                'message' => __('user-tenant::api.no_data'),
                'tenants' => $listTenant,
            ];
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.success'),
            'tenants' => $listTenant,
        ];
    }

    public function getTenant($id): array
    {
        $tenant = Tenant::query()
            ->find($id);

        if (!$tenant) {
            return [
                'status'  => ResponseEnum::CODE_NOT_FOUND,
                'message' => __('user-tenant::api.data_not_found'),
                'tenant'  => $tenant,
            ];
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.updated'),
            'tenant'  => $tenant,
        ];
    }

    public function createTenant($data): array
    {
        $user = get_user();

        if (isset($user->tenant_id)) {
            return [
                'status'  => ResponseEnum::CODE_OK,
                'message' => __('user-tenant::api.tenants.conflict'),
                'tenant'  => null,
            ];
        }

        $data['status'] = TenantEnum::STATUS_ACTIVE;

        foreach (array_keys(TenantEnum::META_ATTR) as $key) {
            if (isset($data[$key])) {
                $data['meta_attribute'][$key] = $data[$key];
                unset($data[$key]);
            }
        }

        $data['meta_attribute'] = json_encode($data['meta_attribute']);

        $data['start_at'] = date("Y-m-d H:i:s");
        $data['user_id']  = $user->id;

        $tenant = Tenant::query()->create($data);

        $user->update(['tenant_id' => $tenant->id]);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.created'),
            'tenant'  => $tenant,
        ];
    }

    public function updateTenant($id, $data): array
    {
        $tenant = Tenant::query()
            ->find($id);

        if (!$tenant) {
            return [
                'status'  => ResponseEnum::CODE_NOT_FOUND,
                'message' => __('user-tenant::api.data_not_found'),
                'tenant'  => $tenant,
            ];
        }

        foreach (array_keys(TenantEnum::META_ATTR) as $key) {
            if (isset($data[$key])) {
                $data['meta_attribute'][$key] = $data[$key];
                unset($data[$key]);
            }
        }

        $data['meta_attribute'] = json_encode($data['meta_attribute']);

        $tenant->fill($data);

        if ($tenant->isDirty()) {
            $tenant->update();
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.updated'),
            'tenant'  => $tenant,
        ];
    }

    public function deleteTenant($id): array
    {
        $tenant = Tenant::query()
            ->find($id);

        if (!$tenant) {
            return [
                'status'  => ResponseEnum::CODE_NOT_FOUND,
                'message' => __('user-tenant::api.data_not_found'),
                'tenant'  => $tenant,
            ];
        }

        $tenant->delete();

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.deleted'),
            'tenant'  => null,
        ];
    }
}
