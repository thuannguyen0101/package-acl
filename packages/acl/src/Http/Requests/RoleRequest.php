<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;

class RoleRequest extends FormRequest
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
        return [
            'name'           => 'required|unique:roles,name,' . $request->id ?? 0,
            'permission_ids' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('acl::api.required', ['attribute' => 'tên vai trò']),
            'name.unique'   => __('acl::api.unique', ['attribute' => 'tên vai trò']),

            'permission_ids.array' => __('acl::api.array', ['attribute' => 'quyền']),
        ];
    }
}
