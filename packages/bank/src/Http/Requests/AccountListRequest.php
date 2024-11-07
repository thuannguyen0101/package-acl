<?php

namespace Workable\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\ACL\Rules\ValidFields;
use Workable\Bank\Enums\AccountEnum;

class AccountListRequest extends FormRequest
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
        $validFields = [
            'user' => ['id', 'name', 'email'],
            'with' => ['user']
        ];

        return [
            'with'        => ['nullable', new ValidFields('with', $validFields['with'])],
            'fields.user' => ['nullable', new ValidFields('user', $validFields['user'])],
        ];
    }

    public function messages()
    {
        return [
            'with.*'      => 'Một hoặc nhiều mối quan hệ được yêu cầu không hợp lệ.',
            'fields.user' => 'Một hoặc nhiều trường được yêu cầu không hợp lệ.',
        ];
    }
}
