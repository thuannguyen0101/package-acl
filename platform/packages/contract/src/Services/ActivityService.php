<?php

namespace Workable\Contract\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\Contract\Enums\ResponseEnum;

use Workable\Contract\Http\DTO\ActivityDTO;
use Workable\Contract\Models\Activity;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class ActivityService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function index(array $request = []): array
    {
        $filters    = $this->getFilterRequest($request);
        $isPaginate = $request['is_paginate'] ?? false;

        $query = $this->buildQuery($filters, is_admin($request));

        if ($isPaginate) {
            $items = $query->paginate(($request['per_page'] ?? 15));
        } else {
            $items = $query->get();
        }

        $items = ActivityDTO::transform($items, $filters);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('budget::api.success'),
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

        $item = ActivityDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $user                  = get_user();
        $request['tenant_id']  = $user->tenant_id;
        $request['created_by'] = $user->id;
        $request['meta']       = json_encode($request['meta']);

        $item = Activity::query()->create($request);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'item'    => ActivityDTO::transform($item)
        ];
    }

    public function update(int $id, array $request = []): array
    {
        $item = $this->findOne($id);

        if (!$item) {
            return $this->returnNotFound();
        }

        $request['meta'] = json_encode($request['meta']);

        $item->fill($request);

        if ($item->isDirty()) {
            $item->update();
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'item'    => ActivityDTO::transform($item)
        ];
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
        $query = Activity::query();

        if (!$isAdmin) {
            $query->where('tenant_id', get_tenant_id());
        }

        $this->scopeFilter($query, $filters['filter_base']);

        $this->scopeSort($query, $filters['orders']);


        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }

        return $query;
    }

    private function findOne(int $id)
    {
        return Activity::query()->where('tenant_id', get_tenant_id())->find($id);
    }

    private function returnNotFound(): array
    {
        return [
            'status'  => ResponseEnum::CODE_NOT_FOUND,
            'message' => "",
            'budget'  => null
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
}
