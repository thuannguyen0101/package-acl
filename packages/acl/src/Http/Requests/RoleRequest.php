<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\MessageValidateTrait;
use Workable\Support\Traits\ResponseHelperTrait;

class RoleRequest extends FormRequest
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
            'name'           => ['required', 'unique:roles,name,' . $request->id ?? 0],
            'permission_ids' => ['nullable', 'array'],
        ];
    }

    public function messages()
    {
        $rules = [
            'name'           => ['unique'],
            'permission_ids' => ['array'],
        ];

        return $this->getMessage($rules);
    }
}
