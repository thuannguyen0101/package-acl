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
                'id'             => $this->id,
                'name'           => $this->name,
                'email'          => $this->email,
                'phone'          => $this->phone,
                'status'         => TenantEnum::getStatus($this->status),
                'address'        => $this->address,
                'full_name'      => $this->full_name,
                'description'    => $this->description,
                'business_phone' => $this->business_phone,
                'meta_attribute' => $this->meta_attribute,
                'gender'         => TenantEnum::getGender($this->gender),
                'birthday'       => TenantEnum::convertDate($this->birthday),
                'citizen_id'     => $this->citizen_id,
                'start_at'       => $this->start_at,
                'expiry_at'      => $this->expiry_at
            ]
        ];
    }
}
