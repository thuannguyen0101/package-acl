<?php

namespace Workable\UserTenant\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\UserTenant\Enums\TenantEnum;

class TenantCollection extends ResourceCollection
{
    use FilterBuilderTrait;

    public function toArray($request): array
    {
        $relations = $this->getFilterRelationsApi($request->all());

        $this->collection->transform(function ($item) use ($relations) {
            $dataRes = [
                'id'         => $item->id,
                'name'       => $item->name,
                'email'      => $item->email,
                'phone'      => $item->phone,
                'status'     => TenantEnum::getStatus($item->status),
                'address'    => $item->address,
                'gender'     => TenantEnum::getGender($item->gender),
                'birthday'   => TenantEnum::convertDate($item->birthday),
                'size'       => $item->size,
                'citizen_id' => $item->citizen_id,
                'start_at'   => $item->start_at,
                'expiry_at'  => $item->expiry_at
            ];

            if (!empty($relations['with'])) {
                foreach ($relations['with'] as $relation) {
                    $dataRes[$relation] = $item->$relation->makeHidden(['pivot', 'created_at', 'updated_at']);
                }
            }
            return $dataRes;
        });
        return [
            "tenants" => $this->collection
        ];
    }
}
