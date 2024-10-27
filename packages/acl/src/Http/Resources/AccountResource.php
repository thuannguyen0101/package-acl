<?php

namespace Workable\ACL\Http\Resources;

use Workable\ACL\Enums\AccountEnum;

class AccountResource extends BaseResource
{
    public function toArray($request): array
    {
        $account = $this->account;

        return [
            "user"    => [
                "id"    => $this->id,
                "name"  => $this->name,
                "email" => $this->email,
            ],
            "account" => [
                'account_id'     => $account->id,
                'account_number' => $account->account_number,
                'balance'        => $account->balance,
                'account_type'   => AccountEnum::TYPE_TEXT[$account->account_type],
                'bank_name'      => AccountEnum::BANK_NAME_TEXT[$account->bank_name],
                'branch_name'    => AccountEnum::BRANCH_NAME_TEXT[$account->branch_name],
                'status'         => AccountEnum::STATUS_TEXT[$account->status],
            ]
        ];
    }
}
