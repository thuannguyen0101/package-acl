<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;

class RoleRequest extends FormRequest
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
            'name'                  => 'required|unique:roles,name,' . $request->id ?? 0,
            'permission_ids'        => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên vai trò không được để trống.',
            'name.unique'   => 'Tên vai trò đã tồn tại.',

            'permission_ids.array'        => 'Dữ liệu quyền không hợp lệ.',
        ];
    }
}
