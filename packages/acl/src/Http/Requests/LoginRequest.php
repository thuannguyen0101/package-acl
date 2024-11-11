<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\MessageValidateTrait;
use Workable\Support\Traits\ResponseHelperTrait;

class LoginRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

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
            'username' => ['required', 'alpha_num', 'string', 'min:3', 'max:255'],
            'password' => ['required', 'string', 'min:8',],
        ];
    }

    public function messages()
    {
        $rules = [
            'username' => ['required', 'alpha_num', 'string', 'min:3', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ];

        return $this->getMessage($rules);
    }
}
