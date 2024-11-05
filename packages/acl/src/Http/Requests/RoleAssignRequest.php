<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;

class RoleAssignRequest extends FormRequest
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
            'check'          => 'required|boolean',
            'role_id'        => 'required',
            'permission_ids' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required'        => 'Vãi trò không được để trống.',
            'permission_ids.array'    => 'Dữ liệu quyền không hợp lệ.',
            'permission_ids.required' => 'Dữ liệu quyền không được để trống.',
        ];
    }
}
