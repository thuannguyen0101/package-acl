<?php

namespace Workable\UserTenant\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    protected $token = '';

    public function setToken(string $token = '')
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function toArray($request): array
    {
        return [
            "user"  => [
                "id"       => $this->id,
                "username" => $this->username,
                "email"    => $this->email,
            ],
            "token" => $this->getToken()
        ];
    }
}
