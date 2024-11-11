<?php

namespace Workable\UserTenant\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Workable\UserTenant\Enums\TenantEnum;

class TenantResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "tenant" => [
                'id'         => $this->id,
                'name'       => $this->name,
                'email'      => $this->email,
                'phone'      => $this->phone,
                'status'     => TenantEnum::getStatus($this->status),
                'address'    => $this->address,
                'gender'     => TenantEnum::getGender($this->gender),
                'birthday'   => TenantEnum::convertDate($this->birthday),
                'size'       => $this->size,
                'citizen_id' => $this->citizen_id,
                'start_at'   => $this->start_at,
                'expiry_at'  => $this->expiry_at
            ]
        ];
    }
}
