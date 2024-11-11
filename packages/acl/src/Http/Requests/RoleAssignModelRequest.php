<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\MessageValidateTrait;
use Workable\Support\Traits\ResponseHelperTrait;

class RoleAssignModelRequest extends FormRequest
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
            'role_id'  => ['required'],
            'model_id' => ['required'],
        ];
    }

    public function messages()
    {
        $rules = [
            'role_id'  => ['required'],
            'model_id' => ['required'],
        ];

        return $this->getMessage($rules);
    }
}
