<?php

namespace Workable\HRM\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\DTO\PenaltyDTO;
use Workable\HRM\Models\Penalty;
use Workable\HRM\Models\PenaltyRule;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class PenaltyService
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

        $items = PenaltyDTO::transform($items, $filters);

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

        $item = PenaltyDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $user = get_user();

        $rule = PenaltyRule::query()->where('tenant_id', $user['tenant_id'])
            ->where('id', $request['rule_id'])->first();

        if (!$rule) {
            return $this->returnNotFound();
        }

        $request['user_id']    = $user->id;
        $request['tenant_id']  = $user->tenant_id;
        $request['created_by'] = $user->id;
        $request['updated_by'] = $user->id;
        $request['fine_type']  = $rule->type;


        $item = Penalty::query()->create($request);
        $item = PenaltyDTO::transform($item);

        return $this->returnSuccess($item);
    }

    public function update(int $id, array $request = []): array
    {
        $item = $this->findOne($id);
        if (!$item) {
            return $this->returnNotFound();
        }

        $rule = PenaltyRule::query()->where('tenant_id', get_tenant_id())
            ->where('id', $request['rule_id'])->first();

        if (!$rule) {
            return $this->returnNotFound();
        }

        $item->fill($request);

        if ($item->isDirty()) {
            $item->updated_by = get_user_id();
            $item->update();
        }

        $item = PenaltyDTO::transform($item);

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
        $query = Penalty::query();

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
        return Penalty::query()->where('tenant_id', get_tenant_id())->find($id);
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
