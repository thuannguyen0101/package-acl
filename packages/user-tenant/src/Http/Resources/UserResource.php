<?php

namespace Workable\UserTenant\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Workable\UserTenant\Enums\UserEnum;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            "user" => [
                'id'         => $this->id,
                'username'   => $this->username,
                'email'      => $this->email,
                'phone'      => $this->phone,
                'address'    => $this->address,
                'status'     => UserEnum::getStatus($this->status),
                'gender'     => UserEnum::getGender($this->gender),
                'birthday'   => UserEnum::convertDate($this->birthday),
                'avatar'     => $this->avatar ?? null,
                'tenant'     => $this->tenant ?? [],
                'created_by' => $this->created_by ?? null,
                'updated_by' => $this->updated_by ?? null,
            ]
        ];
    }
}
