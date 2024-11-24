<?php

namespace Workable\Budget\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Budget\Http\DTO\AccountMoneyDTO;
use Workable\Budget\Models\AccountMoney;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class AccountMoneyService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function index(array $request = []): array
    {
        $filters    = $this->getFilterRequest($request);
        $isPaginate = $request['is_paginate'] ?? false;
        $query      = $this->buildQuery($filters, is_admin($request));

        if ($isPaginate) {
            $accountMonies = $query->paginate(($request['per_page'] ?? 15));
        } else {
            $accountMonies = $query->get();
        }

        $accountMonies = AccountMoneyDTO::transform($accountMonies, $filters);

        return [
            'status'         => ResponseMessageEnum::CODE_OK,
            'message'        => __('budget:api.success'),
            'account_monies' => $accountMonies
        ];
    }

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $accountMoney = $this->buildQuery($filters, is_admin($request))->find($id);

        if (!$accountMoney) {
            return $this->returnNotFound();
        }

        $accountMoney = AccountMoneyDTO::transform($accountMoney, $filters);

        return $this->returnSuccess($accountMoney);
    }

    public function store(array $request = []): array
    {
        $user    = get_user();
        $default = [
            'area_id'        => 0,
            'area_source_id' => 0,
        ];

        $data = array_merge($default, [
            'tenant_id'   => $user->tenant_id,
            'name'        => $request['name'],
            'description' => $request['description'],
            'created_by'  => $user->id,
            'updated_by'  => $user->id,
        ]);

        $accountMoney = AccountMoney::query()->create($data);

        $accountMoney = AccountMoneyDTO::transform($accountMoney);

        return $this->returnSuccess($accountMoney, __('budget:api.created'));
    }

    public function update(int $id, array $request = []): array
    {
        $accountMoney = $this->findOne($id);

        if (!$accountMoney) {
            return $this->returnNotFound();
        }

        $accountMoney->fill($request);

        if ($accountMoney->isDirty()) {
            $accountMoney->updated_by = get_user_id();
            $accountMoney->update();
        }

        $accountMoney = AccountMoneyDTO::transform($accountMoney);

        return $this->returnSuccess($accountMoney, __('budget:api.updated'));
    }

    public function destroy(int $id): array
    {
        $accountMoney = $this->findOne($id);

        if (!$accountMoney) {
            return $this->returnNotFound();
        }

        $accountMoney->delete();

        return $this->returnSuccess($accountMoney, __('budget:api.deleted'));
    }

    private function buildQuery(array $filters = [], bool $isAdmin = false): Builder
    {
        $query = AccountMoney::query();

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
        return AccountMoney::query()->where('tenant_id', get_tenant_id())->find($id);
    }

    private function returnNotFound(): array
    {
        return [
            'status'        => ResponseMessageEnum::CODE_NOT_FOUND,
            'message'       => __('budget:api.not_found'),
            'account_money' => null
        ];
    }

    private function returnSuccess($accountMoney, string $message = '', array $filters = []): array
    {
        return [
            'status'        => ResponseMessageEnum::CODE_OK,
            'message'       => $message ?: __('budget:api.success'),
            'account_money' => $accountMoney
        ];
    }
}
