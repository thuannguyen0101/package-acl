<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;

class PermissionRequest extends FormRequest
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
        return [
            'role_ids'   => 'required|array',
            'role_ids.*' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'role_ids.required'  => 'Vai trò không được để trống.',
            'role_ids.array'     => 'Dữ liệu vai trò không hợp lệ.',
            'role_ids.*.numeric' => 'Dữ liệu quyền không hợp lệ',
        ];
    }
}
