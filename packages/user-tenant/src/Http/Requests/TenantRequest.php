<?php

namespace Workable\UserTenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Enums\TenantEnum;

class TenantRequest extends FormRequest
{
    use ResponseHelperTrait;

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
        return [
            'name'       => ['required', 'string', 'min:3', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:tenants,email,' . request('id', 0)],
            'phone'      => ['required', 'string', 'min:10', 'max:11', 'unique:tenants,phone,' . request('id', 0)],
            'address'    => ['nullable', 'string'],
            'gender'     => ['nullable', 'numeric', 'in:' . implode(',', array_keys(TenantEnum::GENDER))],
            'birthday'   => ['nullable', 'date'],
            'size'       => ['nullable', 'numeric'],
            'citizen_id' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('acl::api.required', [
                'attribute' => __('user-tenant::tenant.fields.name')
            ]),
            'name.string'   => __('acl::api.validation_data', [
                'attribute' => __('user-tenant::tenant.fields.name'),
                'type'      => __('user-tenant::tenant.fields.string')
            ]),
            'name.min'      => __('acl::api.min_length', [
                'attribute' => __('user-tenant::tenant.fields.name')
            ]),
            'name.max'      => __('acl::api.max_length', [
                'attribute' => __('user-tenant::tenant.fields.name')
            ]),

            'email.required' => __('acl::api.required', [
                'attribute' => __('user-tenant::tenant.fields.email')
            ]),
            'email.string'   => __('acl::api.validation_data', [
                'attribute' => __('user-tenant::tenant.fields.email'),
                'type'      => __('user-tenant::tenant.fields.string')
            ]),
            'email.max'      => __('acl::api.max_length', [
                'attribute' => __('user-tenant::tenant.fields.email')
            ]),
            'email.email'    => __('acl::api.email', [
                'attribute' => __('user-tenant::tenant.fields.email')
            ]),
            'email.unique'   => __('acl::api.unique', [
                'attribute' => __('user-tenant::tenant.fields.email')
            ]),

            'phone.required' => __('acl::api.required', [
                'attribute' => __('user-tenant::tenant.fields.phone')
            ]),
            'phone.string'   => __('acl::api.validation_data', [
                'attribute' => __('user-tenant::tenant.fields.phone'),
                'type'      => __('user-tenant::tenant.fields.string')
            ]),
            'phone.min'      => __('acl::api.min_length', [
                'attribute' => __('user-tenant::tenant.fields.phone')
            ]),
            'phone.max'      => __('acl::api.max_length', [
                'attribute' => __('user-tenant::tenant.fields.phone')
            ]),
            'phone.unique'   => __('acl::api.unique', [
                'attribute' => __('user-tenant::tenant.fields.phone')
            ]),


            'address.string' => __('acl::api.validation_data', [
                'attribute' => __('user-tenant::tenant.fields.address'),
                'type'      => __('user-tenant::tenant.fields.string')
            ]),

            'gender.numeric' => __('acl::api.validation_data', [
                'attribute' => __('user-tenant::tenant.fields.address'),
                'type'      => __('user-tenant::tenant.fields.string')
            ])
        ];
    }
}
