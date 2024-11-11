<?php

namespace Workable\ACL\Http\Resources;

class UserResource extends BaseResource
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
                "username" => $this->name ?? $this->username,
                "email"    => $this->email,
            ],
            "token" => $this->getToken()
        ];
    }
}
