<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponse;

class PermissionRequest extends FormRequest
{
    use ApiResponse;

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
            'permission_id' => 'required|exists:permissions,id',
            'role_ids'      => 'required|array',
            'role_ids.*'    => 'exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'permission_id.required' => 'Quyền không được để trống.',
            'permission_id.exists'   => 'Quyền không tồn tại',

            'role_ids.required' => 'Vai trò không được để trống.',
            'role_ids.array'    => 'Dữ liệu vai trò không hợp lệ.',
            'role_ids.*.exists' => 'Vai trò không không tồn tại',
        ];
    }
}
