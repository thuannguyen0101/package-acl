<?php

namespace Workable\Bank\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Workable\Bank\Enums\AccountEnum;

class AccountCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $withUser = $request->get("withUser", false);
        $dataRes  = $this->collection->transform(function ($item) use ($withUser) {
            $dataRes = [
                'account_id'     => $item->id,
                'account_number' => $item->account_number,
                'balance'        => $item->balance,
                'account_type'   => AccountEnum::TYPE_TEXT[$item->account_type],
                'bank_name'      => AccountEnum::BANK_NAME_TEXT[$item->bank_name],
                'branch_name'    => AccountEnum::BRANCH_NAME_TEXT[$item->branch_name],
                'status'         => AccountEnum::STATUS_TEXT[$item->status],
            ];
            if ($withUser) {
                $user            = $item->user;
                $dataRes['user'] = [
                    "id"    => $user->id,
                    "name"  => $user->name,
                    "email" => $user->email,
                ];
            }

            return $dataRes;
        })->values();

        return [
            'accounts' => $dataRes
        ];
    }
}
