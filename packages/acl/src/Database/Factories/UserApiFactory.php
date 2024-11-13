<?php

namespace Workable\ACL\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Workable\ACL\Models\User;

class UserApiFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ];
    }
}
