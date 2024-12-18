<?php

namespace Workable\Contract\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\Contract\Enums\ResponseEnum;
use Workable\Contract\Enums\TransactionEnum;
use Workable\Contract\Http\DTO\TransactionDTO;
use Workable\Contract\Models\Transaction;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class TransactionService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function __construct()
    {

    }

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

        $items = TransactionDTO::transform($items, $filters);

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

        $item = TransactionDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $user              = get_user();
        $request['status'] = $user['status'] ?? TransactionEnum::STATUS_PENDING;

        $request['tenant_id']  = $user->tenant_id;
        $request['created_by'] = $user->id;
        $request['updated_by'] = $user->id;

        $item = Transaction::query()->create($request);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'item'    => TransactionDTO::transform($item)
        ];
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

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'item'    => TransactionDTO::transform($item)
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
        $query = Transaction::query();

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
        return Transaction::query()->where('tenant_id', get_tenant_id())->find($id);
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
