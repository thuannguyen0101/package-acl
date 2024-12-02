<?php

namespace Workable\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Rules\ValidFields;
use Workable\Support\Traits\ResponseHelperTrait;

class AccountListRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validFields = [
            'user'   => ['id', 'name', 'email'],
            'tenant' => ['name', 'email', 'user_id', 'phone', 'status', 'address', 'full_name', 'description',
                'business_phone', 'meta_attribute', 'website', 'gender', 'birthday', 'size', 'citizen_id', 'start_at', 'expiry_at'],
            'with'   => ['user', 'tenant']
        ];

        return [
            'with'          => ['nullable', new ValidFields('with', $validFields['with'])],
            'fields.user'   => ['nullable', new ValidFields('user', $validFields['user'])],
            'fields.tenant' => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
        ];
    }
}
