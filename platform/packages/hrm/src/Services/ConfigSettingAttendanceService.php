<?php

namespace Workable\HRM\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\DTO\ConfigSettingAttendanceDTO;
use Workable\HRM\Models\ConfigSetting;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class ConfigSettingAttendanceService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $item = $this->buildQuery($filters, is_admin($request))->find($id);
        if (!$item) {
            return $this->returnNotFound();
        }

        $item = ConfigSettingAttendanceDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $user                         = get_user();
        $request['user_id']           = $user->id;
        $request['tenant_id']         = $user->tenant_id;
        $request['exclude_weekends']  = json_encode($request['exclude_weekends'] ?? []);
        $request['half_day_weekends'] = json_encode($request['half_day_weekends'] ?? []);
        $request['created_by']        = $user->id;
        $request['updated_by']        = $user->id;

        $item = ConfigSetting::query()->create($request);
        $item = ConfigSettingAttendanceDTO::transform($item);

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

        $config = ConfigSettingAttendanceDTO::transform($config);

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
        $query = ConfigSetting::query();

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
        return ConfigSetting::query()->where('tenant_id', get_tenant_id())->find($id);
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
