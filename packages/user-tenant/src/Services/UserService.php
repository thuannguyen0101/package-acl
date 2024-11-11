<?php

namespace Workable\UserTenant\Services;

use Illuminate\Support\Facades\Hash;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\UserTenant\Enums\ResponseEnum;
use Workable\UserTenant\Enums\UserEnum;
use Workable\UserTenant\Models\User;

class UserService
{
    use FilterBuilderTrait;

    public function getUsers($request): array
    {
        $filter = $this->getFilterRequest($request);
        $query  = User::query();

        if (!empty($filter['with'])) {
            $query->with($filter['with']);
        }

        $listUsers = $query->get();

        if ($listUsers->count() === 0) {
            return [
                'status'  => ResponseEnum::CODE_NO_CONTENT,
                'message' => __('user-tenant::api.no_data'),
                'users'   => $listUsers,
            ];
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.success'),
            'users'   => $listUsers,
        ];
    }

    public function getUser($id): array
    {
        $user = User::query()->find($id);

        if (!$user) {
            return [
                'status'  => ResponseEnum::CODE_NOT_FOUND,
                'message' => __('user-tenant::api.data_not_found'),
                'user'    => $user,
            ];
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.updated'),
            'user'    => $user,
        ];
    }

    public function createUser($data): array
    {
        $data['status']   = UserEnum::STATUS_ACTIVE;
        $data['password'] = Hash::make($data['password']);
        $user             = User::query()->create($data);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.created'),
            'user'    => $user,
        ];
    }

    public function updateUser($id, $data): array
    {
        $user = User::query()->find($id);

        if (!$user) {
            return [
                'status'  => ResponseEnum::CODE_NOT_FOUND,
                'message' => __('user-tenant::api.data_not_found'),
                'user'    => $user,
            ];
        }

        $user->fill($data);

        if ($user->isDirty()) {
            $user->update();
        }

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.updated'),
            'user'    => $user,
        ];
    }

    public function deleteUser($id): array
    {
        $user = User::query()->find($id);

        if (!$user) {
            return [
                'status'  => ResponseEnum::CODE_NOT_FOUND,
                'message' => __('user-tenant::api.data_not_found'),
                'user'    => $user,
            ];
        }

        $user->delete();

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('user-tenant::api.deleted'),
            'user'    => null,
        ];
    }
}
