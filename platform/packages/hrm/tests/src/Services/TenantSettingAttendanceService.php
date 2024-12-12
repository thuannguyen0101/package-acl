<?php

namespace Workable\HRM\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\DTO\TenantSettingAttendanceDTO;
use Workable\HRM\Models\TenantSetting;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class TenantSettingAttendanceService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $item = $this->buildQuery($filters, is_admin($request))->find($id);
        if (!$item) {
            return $this->returnNotFound();
        }

        $item = TenantSettingAttendanceDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $user = get_user();
        $data = [
            'user_id'            => $user->id,
            'tenant_id'          => $user->tenant_id,
            'created_by'         => $user->id,
            'updated_by'         => $user->id,
            'setting_attendance' => json_encode($request),
        ];

        $item = TenantSetting::query()->create($data);

        $item = TenantSettingAttendanceDTO::transform($item);

        return $this->returnSuccess($item);
    }

    public function update(int $id, array $request = []): array
    {
        $config = $this->findOne($id);

        if (!$config) {
            return $this->returnNotFound();
        }

        $request['exclude_weekends']  = json_encode($request['exclude_weekends'] ?? []);
        $request['half_day_weekends'] = json_encode($request['half_day_weekends'] ?? []);

        $config->fill($request);

        if ($config->isDirty()) {
            $config->updated_by = get_user_id();
            $config->update();
        }

        $config = TenantSettingAttendanceDTO::transform($config);

        return $this->returnSuccess($config, "");
    }

    public function destroy(int $id): array
    {
        $item = $this->findOne($id);

        if (!$item) {
            return $this->returnNotFound();
        }

        $item->delete();

        return $this->returnSuccess(null, "");
    }

    private function buildQuery(array $filters = [], bool $isAdmin = false): Builder
    {
        $query = TenantSetting::query();

        if (!$isAdmin) {
            $query->where('tenant_id', get_tenant_id());
        }

        $this->scopeFilter($query, $filters['filters']);

        $this->scopeSort($query, $filters['orders']);

        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }

        return $query;
    }

    private function findOne(int $id)
    {
        return TenantSetting::query()->where('tenant_id', get_tenant_id())->find($id);
    }

    private function returnNotFound(): array
    {
        return [
            'status'        => ResponseEnum::CODE_NOT_FOUND,
            'message'       => __('budget::api.not_found'),
            'account_money' => null
        ];
    }

    private function returnSuccess($item, string $message = ''): array
    {
        return [
            'status'            => ResponseEnum::CODE_OK,
            'message'           => $message ?: "",
            'config_attendance' => $item
        ];
    }
}
