<?php

namespace Workable\ACL\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Workable\ACL\Models\UserApi;

class UserApiFactory extends Factory
{
    protected $model = UserApi::class;

    public function definition()
    {
        return [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ];
    }
}
