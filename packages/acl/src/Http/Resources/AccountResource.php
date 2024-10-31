<?php

namespace Workable\ACL\Http\Resources;

use Workable\ACL\Enums\AccountEnum;

class AccountResource extends BaseResource
{
    public function toArray($request): array
    {
        $user = $this->user;

        return [
            "user"    => [
                "id"    => $user->id,
                "name"  => $user->name,
                "email" => $user->email,
            ],
            "account" => [
                'account_id'     => $this->id,
                'account_number' => $this->account_number,
                'balance'        => $this->balance,
                'account_type'   => AccountEnum::TYPE_TEXT[$this->account_type],
                'bank_name'      => AccountEnum::BANK_NAME_TEXT[$this->bank_name],
                'branch_name'    => AccountEnum::BRANCH_NAME_TEXT[$this->branch_name],
                'status'         => AccountEnum::STATUS_TEXT[$this->status],
            ]
        ];
    }
}
