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
                "id"          => $this->id,
                "name"        => $this->name,
                "email"       => $this->email,
                'roles'       => $this->getRoleNames(),
                'permissions' => $this->getAllPermissions()->pluck('name')
            ],
            "token" => $this->getToken()
        ];
    }
}
