<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\HandleFailedValidationTrait;

class RegisterRequest extends FormRequest
{
    use HandleFailedValidationTrait;

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
            'name'     => 'required',
            'email'    => 'email|required|unique:users,email',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'Email đăng nhập không được để trống.',
            'email.email'       => 'Email không hợp lệ. Vui lòng nhập một địa chỉ email hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min'      => 'Mật khẩu phải có ít nhất gồm 6 ký tự.',
            'name'              => 'Tên người dùng không được để trống.',
        ];
    }
}
