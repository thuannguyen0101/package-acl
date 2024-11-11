<?php

namespace Workable\UserTenant\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\UserTenant\Enums\TenantEnum;

class UserCollection extends ResourceCollection
{
    use FilterBuilderTrait;

    public function toArray($request): array
    {
        $relations = $this->getFilterRelationsApi($request->all());

        $this->collection->transform(function ($item) use ($relations) {
            $dataRes = [
                'id'         => $item->id,
                'username'   => $item->username,
                'email'      => $item->email,
                'phone'      => $item->phone,
                'address'    => $item->address,
                'status'     => TenantEnum::getStatus($item->status),
                'gender'     => TenantEnum::getGender($item->gender),
                'birthday'   => TenantEnum::convertDate($item->birthday),
                'avatar'     => $item->avatar ?? '',
                'tenant'     => $item->tenant ?? [],
                'created_by' => '',
                'updated_by' => '',
            ];

            if (!empty($relations['with'])) {
                foreach ($relations['with'] as $relation) {
                    $dataRes[$relation] = $item->$relation->makeHidden(['pivot', 'created_at', 'updated_at']);
                }
            }
            return $dataRes;
        });
        return [
            "users" => $this->collection
        ];
    }
}
