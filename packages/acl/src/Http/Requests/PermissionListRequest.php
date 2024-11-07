<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\ACL\Rules\ValidFields;

class PermissionListRequest extends FormRequest
{
    use ApiResponseTrait;

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
            'filters'      => ['array'],
            'filters.*'    => 'string',
        ];
    }

    public function messages()
    {
        return [
            'with.*'       => 'Một hoặc nhiều mối quan hệ được yêu cầu không hợp lệ.',
            'fields.roles' => 'Một hoặc nhiều trường được yêu cầu không hợp lệ.',
            'filters'      => 'Bộ lọc vai trò phải là mảng.',
            'filters.*'    => 'Bộ lọc vai trò phải là chuỗi.',
        ];
    }
}
