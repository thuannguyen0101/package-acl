<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;

class RegisterRequest extends FormRequest
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
            'email'    => 'email|required|unique:users,email,' . request('id', 0),
            'password' => 'required|min:6',
            'name'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'Email đăng nhập không được để trống.',
            'email.email'       => 'Email không hợp lệ. Vui lòng nhập một địa chỉ email hợp lệ.',
            'email.unique'      => 'Email đã tồn tại trong hệ thống. Vui lòng nhập một địa chỉ email khác.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min'      => 'Mật khẩu phải có ít nhất gồm 6 ký tự.',
            'name'              => 'Tên người dùng không được để trống.',
        ];
    }
}
