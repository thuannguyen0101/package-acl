<?php

namespace Workable\HRM\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Workable\HRM\Enums\LeaveRequestEnum;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\DTO\LeaveRequestDTO;
use Workable\HRM\Models\LeaveRequest;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class LeaveRequestService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    protected $settings;

    public function __construct()
    {
        $settingsService = SettingLoaderService::getInstance(get_tenant_id());
        $this->settings  = $settingsService->get();
    }

    public function index(array $request = []): array
    {
        $filters    = $this->getFilterRequest($request);
        $isPaginate = $request['is_paginate'] ?? false;
        $query      = $this->buildQuery($filters, is_admin($request));

        if ($isPaginate) {
            $items = $query->paginate(($request['per_page'] ?? 15));
        } else {
            $items = $query->get();
        }

        $items = LeaveRequestDTO::transform($items, $filters);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'items'   => $items
        ];
    }

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $item = $this->buildQuery($filters, is_admin($request))->find($id);

        if (!$item) {
            return $this->returnNotFound();
        }

        $item = LeaveRequestDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $request['status'] = $request['status'] ?? LeaveRequestEnum::PENDING;
        $this->setLeaveRequestData($request);
        $item = LeaveRequest::query()->create($request);
        $item = LeaveRequestDTO::transform($item);

        return $this->returnSuccess($item);
    }

    public function update(int $id, array $request = []): array
    {
        $item = $this->findOne($id);

        if (!$item) {
            return $this->returnNotFound();
        }

        $item->fill($request);

        if ($item->isDirty()) {
            $item->update();
        }

        $item = LeaveRequestDTO::transform($item);

        return $this->returnSuccess($item, "");
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
        $query = LeaveRequest::query();

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
        return LeaveRequest::query()->where('tenant_id', get_tenant_id())->find($id);
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
            'status'  => ResponseEnum::CODE_OK,
            'message' => $message ?: "",
            'item'    => $item
        ];
    }

    private function setLeaveRequestData(array $request): array
    {
        $shift_start_time = $this->settings['shift_start_time'];
        $break_end_time   = $this->settings['break_end_time'];
        $start = $request['start_date'];
        $end = $request['start_date'];

        if (array_key_exists($request['leave_type'], LeaveRequestEnum::LEAVE_TYPE_SUB)) {
            if ()
            $hours = Carbon::parse($request['start_date'])->diffInHours(Carbon::parse($request['end_date']));
        }
    }
}
