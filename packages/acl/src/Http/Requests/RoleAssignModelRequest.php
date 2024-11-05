<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;

class RoleAssignModelRequest extends FormRequest
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
            'role_id'  => 'required',
            'model_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required'  => 'Vãi trò không được để trống.',
            'model_id.required' => 'Dữ liệu người dung không được để trống.',
        ];
    }
}
