<?php

namespace Workable\Bank\Http\Resources;

use Workable\ACL\Http\Resources\BaseResource;
use Workable\Bank\Enums\AccountEnum;

class AccountResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            "account" => AccountEnum::dataTransform($this)

        ];
    }
}
