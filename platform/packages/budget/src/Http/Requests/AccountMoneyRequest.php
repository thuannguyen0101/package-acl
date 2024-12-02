<?php

namespace Workable\Budget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Traits\MessageValidateTrait;

class AccountMoneyRequest extends formRequest
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
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return $this->getMessage(
            [
                'name.account_money'        => ['required', 'string', 'max:255'],
                'description.account_money' => ['string'],
            ],
            'budget::api'
        );
    }
}
