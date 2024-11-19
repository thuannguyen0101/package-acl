<?php

namespace Workable\Bank\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Workable\Bank\Enums\AccountEnum;
use Workable\Support\Traits\FilterBuilderTrait;

class AccountCollection extends ResourceCollection
{
    use FilterBuilderTrait;

    public function toArray($request): array
    {
        $relations = $this->getFilterRelationsApi($request->all());

        $dataRes = $this->collection->transform(function ($item) use ($relations) {
            $dataRes = AccountEnum::dataTransform($item);

            return $dataRes;
        })->values();

        return [
            'accounts' => $dataRes
        ];
    }
}
