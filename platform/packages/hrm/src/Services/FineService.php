<?php

namespace Workable\HRM\Services;

use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class FineService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

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

        $items = PenaltyRuleDTO::transform($items, $filters);

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

        $item = PenaltyRuleDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $user                  = get_user();
        $request['user_id']    = $user->id;
        $request['tenant_id']  = $user->tenant_id;
        $request['created_by'] = $user->id;
        $request['updated_by'] = $user->id;
        $request['config']     = json_encode($request['config'] ?? []);

        $item = PenaltyRule::query()->create($request);
        $item = PenaltyRuleDTO::transform($item);

        return $this->returnSuccess($item);
    }

    public function update(int $id, array $request = []): array
    {
        $item = $this->findOne($id);

        if (!$item) {
            return $this->returnNotFound();
        }

        $request['config'] = json_encode($request['config'] ?? []);

        $item->fill($request);

        if ($item->isDirty()) {
            $item->updated_by = get_user_id();
            $item->update();
        }

        $item = PenaltyRuleDTO::transform($item);

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
        $query = PenaltyRule::query();

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
        return PenaltyRule::query()->where('tenant_id', get_tenant_id())->find($id);
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
}
