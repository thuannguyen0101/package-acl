<?php

namespace Workable\Budget\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Budget\Http\DTO\BudgetDTO;
use Workable\Budget\Models\Budget;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class BudgetService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function index(array $request = []): array
    {
        $filters    = $this->getFilterRequest($request);
        $isPaginate = $request['is_paginate'] ?? false;
        $query      = $this->buildQuery($filters, is_admin($request));

        if ($isPaginate) {
            $budgets = $query->paginate(($request['per_page'] ?? 15));
        } else {
            $budgets = $query->get();
        }

        $budgets = BudgetDTO::transform($budgets, $filters);

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('budget::api.success'),
            'budgets' => $budgets
        ];
    }

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $budget = $this->buildQuery($filters, is_admin($request))->find($id);

        if (!$budget) {
            return $this->returnNotFound();
        }

        $budget = BudgetDTO::transform($budget, $filters);

        return $this->returnSuccess($budget);
    }

    public function store(array $request = []): array
    {
        $user = get_user();

        $default = [
            'area_id'        => 0,
            'area_source_id' => 0,
        ];

        $data = array_merge($default, [
            'tenant_id'           => $user->tenant_id,
            'name'                => $request['name'],
            'description'         => $request['description'],
            'money'               => $request['money'],
            'meta_content'        => json_encode($request['meta_content']),
            'meta_file'           => json_encode([]),
            'expense_category_id' => $request['expense_category_id'],
            'account_money_id'    => $request['account_money_id'],
            'created_by'          => $user->id,
            'updated_by'          => $user->id,
        ]);

        $budget = Budget::query()->create($data);

        $budget = BudgetDTO::transform($budget);

        return $this->returnSuccess($budget, __('budget::api.created'));
    }

    public function update(int $id, array $request = []): array
    {
        $budget = $this->findOne($id);

        if (!$budget) {
            return $this->returnNotFound();
        }

        $budget->fill($request);

        if ($budget->isDirty()) {
            $budget->updated_by = get_user_id();
            $budget->updated_at = now();
            $budget->update();
        }

        $budget = BudgetDTO::transform($budget);

        return $this->returnSuccess($budget, __('budget::api.updated'));
    }

    public function destroy(int $id): array
    {
        $budget = $this->findOne($id);

        if (!$budget) {
            return $this->returnNotFound();
        }

        $budget->delete();

        return $this->returnSuccess($budget, __('budget::api.deleted'));
    }

    private function buildQuery(array $filters = [], bool $isAdmin = false): Builder
    {
        $query = Budget::query();

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
        return Budget::query()->where('tenant_id', get_tenant_id())->find($id);
    }

    private function returnNotFound(): array
    {
        return [
            'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
            'message' => __('budget::api.not_found'),
            'budget'  => null
        ];
    }

    private function returnSuccess($budget, string $message = ''): array
    {
        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => $message ?: __('budget::api.success'),
            'budget'  => $budget
        ];
    }
}
