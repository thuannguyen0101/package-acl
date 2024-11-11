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
        return $this->getMessage(
            [
                'name'       => ['required', 'string', 'min:3', 'max:255'],
                'email'      => ['required', 'string', 'email', 'max:255', 'unique'],
                'phone'      => ['required', 'string', 'min:10', 'max:11', 'unique'],
                'address'    => ['string'],
                'gender'     => ['numeric', 'in'],
                'birthday'   => ['date'],
                'size'       => ['numeric'],
                'citizen_id' => ['string'],
            ]
        );
    }

    public function getMessage(array $validates = []): array
    {
        $messages = [];
        foreach ($validates as $key => $rules) {
            foreach ($rules as $v) {
                $rule                      = explode(":", ($v ?? ''));
                $messages["$key.$rule[0]"] = __('user-tenant::api.field_validates.' . $rule[0], [
                    'attribute' => __('user-tenant::api.fields.' . $key),
                    'type'      => __('user-tenant::api.fields.' . $v),
                    'max'       => $rule[1] ?? 0,
                    'min'       => $rule[1] ?? 0
                ]);
            }
        }
        return $messages;
    }
}
