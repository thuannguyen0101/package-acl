<?php

namespace Workable\UserTenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Traits\MessageValidateTrait;

class UserRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        $rules = [
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . request('id', 0)],
            'username' => ['required', 'alpha_num', 'string', 'min:3', 'max:255', 'unique:users,username,' . request('id', 0)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'    => ['required', 'string', 'min:10', 'max:11', 'unique:users,phone,' . request('id', 0)],
            'address'  => ['required', 'string'],

            'gender'   => ['nullable', 'numeric', 'in:' . implode(',', array_keys(TenantEnum::GENDER))],
            'birthday' => ['nullable', 'date'],
            'avatar'   => ['nullable', 'string'],
        ];

        if (request()->method() == 'PUT') {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function messages(): array
    {
        $rules = [
            'email'    => ['required', 'string', 'email', 'max:255', 'unique'],
            'username' => ['required', 'alpha_num', 'string', 'min:3', 'max:255', 'unique'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'    => ['required', 'string', 'min:10', 'max:11', 'unique'],
            'address'  => ['required', 'string'],

            'gender'   => ['nullable', 'numeric', 'in'],
            'birthday' => ['nullable', 'date'],
            'avatar'   => ['nullable', 'string'],
        ];

        if (request()->method() == 'PUT') {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $this->getMessage($rules, 'user-tenant::api');
    }
}
