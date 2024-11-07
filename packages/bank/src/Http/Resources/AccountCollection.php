<?php

namespace Workable\Bank\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Workable\ACL\Core\Traits\FilterApiTrait;
use Workable\Bank\Enums\AccountEnum;

class AccountCollection extends ResourceCollection
{
    use FilterApiTrait;

    public function toArray($request): array
    {
        $relations = $this->getFilterRelationsApi($request->all());

        $dataRes = $this->collection->transform(function ($item) use ($relations) {
            $dataRes = [
                'account_id'     => $item->id,
                'account_number' => $item->account_number,
                'balance'        => $item->balance,
                'account_type'   => AccountEnum::TYPE_TEXT[$item->account_type],
                'bank_name'      => AccountEnum::BANK_NAME_TEXT[$item->bank_name],
                'branch_name'    => AccountEnum::BRANCH_NAME_TEXT[$item->branch_name],
                'status'         => AccountEnum::STATUS_TEXT[$item->status],
            ];
            if (!empty($relations['with'])) {
                foreach ($relations['with'] as $relation) {
                    $dataRes[$relation] = $item->$relation->makeHidden(['pivot', 'created_at', 'updated_at', 'password', 'remember_token']);
                }
            }

            return $dataRes;
        })->values();

        return [
            'accounts' => $dataRes
        ];
    }
}
