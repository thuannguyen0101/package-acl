<?php

namespace Workable\UserTenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Traits\MessageValidateTrait;

class TenantRequest extends FormRequest
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
        return [
            'name'           => ['required', 'string', 'min:3', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:tenants,email,' . request('id', 0)],
            'phone'          => ['required', 'string', 'min:10', 'max:11', 'unique:tenants,phone,' . request('id', 0)],
            'address'        => ['nullable', 'string'],
            'full_name'      => ['nullable', 'string'],
            'description'    => ['nullable', 'string'],
            'business_phone' => ['nullable', 'min:10', 'max:11', 'unique:tenants,business_phone,' . request('id', 0)],
            'meta_attribute' => ['nullable', 'string', 'max:5000'],
            'gender'         => ['nullable', 'numeric', 'in:' . implode(',', array_keys(TenantEnum::GENDER))],
            'birthday'       => ['nullable', 'date'],
            'size'           => ['nullable', 'numeric'],
            'citizen_id'     => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return $this->getMessage(
            [
                'name'           => ['required', 'string', 'min:3', 'max:255'],
                'email'          => ['required', 'string', 'email', 'max:255', 'unique'],
                'phone'          => ['required', 'string', 'min:10', 'max:11', 'unique'],
                'address'        => ['string'],
                'full_name'      => ['nullable', 'string'],
                'description'    => ['nullable', 'string'],
                'business_phone' => ['nullable', 'min:10', 'max:11', 'unique'],
                'meta_attribute' => ['nullable', 'numeric', 'max:5000'],
                'gender'         => ['numeric', 'in'],
                'birthday'       => ['date'],
                'size'           => ['numeric'],
                'citizen_id'     => ['string'],
            ],
            'user-tenant::api'
        );
    }
}
