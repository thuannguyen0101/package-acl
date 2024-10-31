<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponse;

class RoleRequest extends FormRequest
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
        $rules = [];
        if ($request->method() == 'PUT') {
            $rules = [
                'role_id' => 'required|exists:roles,id',
            ];
        }
        return array_merge($rules, [
            'name'             => 'required|unique:roles,name,' . $request->role_id ?? 0,
            'permission_ids'   => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);
    }

    public function messages()
    {
        return [
            'name.required'           => 'Tên vai trò không được để trống.',
            'name.unique'             => 'Tên vai trò đã tồn tại.',
            'role_id.required'        => 'Vãi trò không được để trống.',
            'role_id.exists'          => 'Vãi trò không tồn tại.',
            'permission_ids.array'    => 'Dữ liệu quyền không hợp lệ.',
            'permission_ids.*.exists' => 'Quyền không tồ tại.',
        ];
    }
}
