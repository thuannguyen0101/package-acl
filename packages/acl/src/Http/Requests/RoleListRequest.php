<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Rules\ValidFields;
use Workable\Support\Traits\ResponseHelperTrait;

class RoleListRequest extends FormRequest
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
            'permissions' => ['id', 'name', 'group', 'guard_name'],
            'with'        => ['permissions']
        ];

        return [
            'with'               => ['nullable', new ValidFields('with', $validFields['with'])],
            'fields.permissions' => ['nullable', new ValidFields('permissions', $validFields['permissions'])],
        ];
    }

    public function messages()
    {
        return [
            'with.*'       => __('acl::api.validation_with'),
            'fields.roles' => __('acl::api.validation_fields'),
        ];
    }
}
