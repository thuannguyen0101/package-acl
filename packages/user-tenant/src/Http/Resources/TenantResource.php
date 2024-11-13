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
                'citizen_id'     => $this->citizen_id,
                'address'        => $this->address,
                'full_name'      => $this->full_name,
                'description'    => $this->description,
                'business_phone' => $this->business_phone,
                'birthday'       => TenantEnum::convertDate($this->birthday),
                'size'           => TenantEnum::getSize($this->size),
                'gender'         => TenantEnum::getGender($this->gender),
                'meta_attribute' => TenantEnum::getMetaAttribute($this->meta_attribute),
                'status'         => TenantEnum::getStatus($this->status),
                'start_at'       => $this->start_at,
                'expiry_at'      => $this->expiry_at
            ]
        ];
    }
}
