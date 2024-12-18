<?php

namespace Workable\Contract\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\Contract\Enums\CRMContractHistoryEnum;
use Workable\Contract\Enums\ResponseEnum;
use Workable\Contract\Http\DTO\CRMContractDTO;
use Workable\Contract\Models\CRMContract;
use Workable\Contract\Enums\CRMContractEnum;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class CRMContractService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    protected $contractHistoryService;

    public function __construct(CRMContractHistoryService $contractHistoryService)
    {
        $this->contractHistoryService = $contractHistoryService;
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

        $items = CRMContractDTO::transform($items, $filters);

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

        $item = CRMContractDTO::transform($item, $filters);

        return $this->returnSuccess($item);
    }

    public function store(array $request = []): array
    {
        $user               = get_user();
        $request['status']  = $user['status'] ?? CRMContractEnum::PENDING_APPROVAL;
        $request['user_id'] = $user['status'] ?? CRMContractEnum::PENDING_APPROVAL;

        $request['tenant_id']  = $user->tenant_id;
        $request['created_by'] = $request['created_by'] ?? $user->id;
        $request['updated_by'] = $user->id;

        $item = CRMContract::query()->create($request);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'item'    => CRMContractDTO::transform($item)
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
            $item->updated_by = get_user_id();
            $item->update();
            $this->contractHistoryService->store($item);
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'item'    => CRMContractDTO::transform($item)
        ];
    }

    public function destroy(int $id): array
    {
        $item = $this->findOne($id);

        if (!$item) {
            return $this->returnNotFound();
        }

        $item->delete();
        $this->contractHistoryService->store($item, CRMContractHistoryEnum::DELETE);

        return $this->returnSuccess(null, "");
    }

    private function buildQuery(array $filters = [], bool $isAdmin = false): Builder
    {
        $query = CRMContract::query();

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
        return CRMContract::query()->where('tenant_id', get_tenant_id())->find($id);
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

    public function get()
    {

    }
}
