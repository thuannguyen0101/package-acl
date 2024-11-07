<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;

class LoginRequest extends FormRequest
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
            'email'    => 'email|required',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => __('acl::api.required', ['attribute' => 'email']),
            'email.email'       => __('acl::api.email', ['attribute' => 'email']),
            'password.required' => __('acl::api.required', ['attribute' => 'mật khẩu']),
            'password.min'      => __('acl::api.min_length', [
                'attribute' => 'mật khẩu',
                'min'       => '6',
            ]),
        ];
    }
}
