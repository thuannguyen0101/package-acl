<?php

namespace Workable\Budget\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\Budget\Enums\ExpenseCategoryEnum;
use Workable\Budget\Enums\ResponseEnum;
use Workable\Budget\Http\DTO\ExpenseCategoryDTO;
use Workable\Budget\Models\ExpenseCategory;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class ExpenseCategoryService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function index(array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $query   = $this->buildQuery($filters, is_admin($request));

        $isPaginate = $request['is_paginate'] ?? false;
        if ($isPaginate) {
            $expenseCategories = $query->paginate(($request['per_page'] ?? 15));
        } else {
            $expenseCategories = $query->get();
        }

        $expenseCategories = ExpenseCategoryDTO::transform($expenseCategories, $filters);

        return [
            'status'             => ResponseEnum::CODE_OK,
            'message'            => __('budget::api.success'),
            'expense_categories' => $expenseCategories
        ];
    }

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $expenseCategory = $this->buildQuery($filters, is_admin($request))->find($id);

        if (!$expenseCategory) {
            return $this->returnNotFound();
        }

        $expenseCategory = ExpenseCategoryDTO::transform($expenseCategory, $filters);

        return $this->returnSuccess($expenseCategory);
    }

    public function store(array $request = []): array
    {
        $user = get_user();

        $default = [
            'area_id'        => 0,
            'area_source_id' => 0,
        ];

        $data = array_merge($default, [
            'tenant_id'   => $user->tenant_id,
            'created_by'  => $user->id,
            'updated_by'  => $user->id,
            'name'        => $request['name'],
            'description' => $request['description'],
            'status'      => $request['status'] ?? ExpenseCategoryEnum::STATUS_ACTIVE,
        ]);

        $expenseCategory = ExpenseCategory::query()->create($data);

        $expenseCategory = ExpenseCategoryDTO::transform($expenseCategory);

        return $this->returnSuccess($expenseCategory, __('budget::api.created'));
    }

    public function update(int $id, array $request = []): array
    {
        $expenseCategory = $this->findOne($id);

        if (!$expenseCategory) {
            return $this->returnNotFound();
        }

        $expenseCategory->fill($request);

        if ($expenseCategory->isDirty()) {
            $expenseCategory->updated_by = get_user_id();
            $expenseCategory->update();
        }

        $expenseCategory = ExpenseCategoryDTO::transform($expenseCategory);

        return $this->returnSuccess($expenseCategory, __('budget::api.updated'));
    }

    public function destroy(int $id): array
    {
        $expenseCategory = $this->findOne($id);

        if (!$expenseCategory) {
            return $this->returnNotFound();
        }

        $expenseCategory->delete();

        return $this->returnSuccess($expenseCategory, __('budget::api.deleted'));
    }

    private function buildQuery(array $filters = [], bool $isAdmin = false): Builder
    {
        $query = ExpenseCategory::query();

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
        return ExpenseCategory::query()->where('tenant_id', get_tenant_id())->find($id);
    }

    private function returnNotFound(): array
    {
        return [
            'status'        => ResponseEnum::CODE_NOT_FOUND,
            'message'       => __('budget::api.not_found'),
            'expense_category' => null
        ];
    }

    private function returnSuccess($accountMoney, string $message = '', array $filters = []): array
    {
        return [
            'status'        => ResponseEnum::CODE_OK,
            'message'       => $message ?: __('budget::api.success'),
            'expense_category' => $accountMoney
        ];
    }
}
