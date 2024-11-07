<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Rules\ValidFields;
use Workable\Support\Traits\ResponseHelperTrait;

class PermissionListRequest extends FormRequest
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
            'roles' => ['id', 'name', 'guard_name'],
            'with'  => ['roles']
        ];

        return [
            'with'         => ['nullable', new ValidFields('with', $validFields['with'])],
            'fields.roles' => ['nullable', new ValidFields('roles', $validFields['roles'])],
            'filters'      => ['nullable', 'array'],
            'filters.*'    => 'string',
        ];
    }

    public function messages()
    {
        return [
            'with.*'       => __('acl::api.validation_with'),
            'fields.roles' => __('acl::api.validation_fields'),
            'filters'      => __('acl::api.array', ['attribute' => 'bộ lọc']),
            'filters.*'    => __('acl::api.array.validation_data', ['attribute' => 'bộ lọc', 'type' => 'chuỗi']),
        ];
    }
}
