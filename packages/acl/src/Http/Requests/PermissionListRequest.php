<?php

namespace Workable\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\MessageValidateTrait;
use Workable\ACL\Rules\ValidFields;
use Workable\Support\Traits\ResponseHelperTrait;

class PermissionListRequest extends FormRequest
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
        $validFields = [
            'roles' => ['id', 'name', 'guard_name'],
            'with'  => ['roles']
        ];

        return [
            'with'         => ['nullable', new ValidFields('with', $validFields['with'])],
            'fields.roles' => ['nullable', new ValidFields('roles', $validFields['roles'])],
            'filters'      => ['nullable', 'array'],
            'filters.*'    => 'string',
        ];
    }

    public function messages()
    {
        $rules = [
            'filters'   => ['array'],
            'filters.*' => ['string'],
        ];

        return $this->getMessage($rules);
    }
}
