<?php

namespace Workable\Navigation\Services;

use Illuminate\Database\Eloquent\Builder;
use Workable\Navigation\Enums\CategoryMultiEnum;
use Workable\Navigation\Enums\ResponseEnum;
use Workable\Navigation\Http\DTO\NavigationDTO;
use Workable\Navigation\Models\CategoryMulti;
use Workable\Navigation\Models\Navigation;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class NavigationService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function index(array $request = []): array
    {
        $filters    = $this->getFilterRequest($request);
        $isPaginate = $request['is_paginate'] ?? false;
        $query      = $this->buildQuery($filters);

        if ($isPaginate) {
            $navigations = $query->paginate(($request['per_page'] ?? 15));
        } else {
            $navigations = $query->get();
        }

        $navigations = NavigationDTO::transform($navigations, $filters);

        return [
            'status'      => ResponseEnum::CODE_OK,
            'message'     => __('category_multi::api.success'),
            'navigations' => $navigations
        ];
    }

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $categoryMulti = $this->buildQuery($filters)->find($id);

        if (!$categoryMulti) {
            return $this->returnNotFound();
        }

        $categoryMulti = NavigationDTO::transform($categoryMulti, $filters);

        return $this->returnSuccess($categoryMulti);
    }

    public function store(array $request = []): array
    {
        $data = [
            'name'       => $request['name'],
            'root_id'    => $request['parent_id'] ?? 0,
            'parent_id'  => $request['parent_id'] ?? 0,
            'url'        => $request['url'],
            'type'       => $request['type'] ?? null,
            'icon'       => $request['icon'] ?? null,
            'view_data'  => $request['view_data'] ?? null,
            'label'      => $request['label'] ?? 0,
            'layout'     => $request['layout'] ?? 0,
            'sort'       => $request['sort'] ?? 0,
            'is_auth'    => $request['is_auth'] ?? 0,
            'status'     => $request['status'] ?? CategoryMultiEnum::STATUS_ACTIVE,
            'meta'       => json_encode($request['meta'] ?? []),
        ];

        $categoryMulti = CategoryMulti::query()->create($data);

        $categoryMulti = NavigationDTO::transform($categoryMulti);


        return $this->returnSuccess($categoryMulti, __('category_multi::api.created'));
    }

    public function update(int $id, array $request = []): array
    {
        $categoryMulti = CategoryMulti::query()->find($id);

        if (!$categoryMulti) {
            return $this->returnNotFound();
        }

        $request['meta'] = json_encode($request['meta'] ?? []);

        $categoryMulti->fill($request);

        if ($categoryMulti->isDirty()) {
            $categoryMulti->updated_by = get_user_id();
            $categoryMulti->updated_at = now();
            $categoryMulti->update();
        }

        $categoryMulti = NavigationDTO::transform($categoryMulti);

        return $this->returnSuccess($categoryMulti, __('category_multi::api.updated'));
    }

    public function destroy(int $id): array
    {
        $categoryMulti = Navigation::query()->find($id);

        if (!$categoryMulti) {
            return $this->returnNotFound();
        }

        $categoryMulti->delete();

        return $this->returnSuccess($categoryMulti, __('category_multi::api.deleted'));
    }

    private function buildQuery(array $filters = []): Builder
    {
        $query = Navigation::query();

        $this->scopeFilter($query, $filters['filters']);

        $this->scopeSort($query, $filters['orders']);

        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }

        return $query;
    }

    private function returnNotFound(): array
    {
        return [
            'status'         => ResponseEnum::CODE_NOT_FOUND,
            'message'        => __('category_multi::api.not_found'),
            'category_multi' => null
        ];
    }

    private function returnSuccess($categoryMulti, string $message = ''): array
    {
        return [
            'status'         => ResponseEnum::CODE_OK,
            'message'        => $message ?: __('category_multi::api.success'),
            'category_multi' => $categoryMulti
        ];
    }
}
